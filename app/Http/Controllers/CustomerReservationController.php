<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\ReservationMenu;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerReservationController extends Controller
{
    /**
     * Halaman Beranda Restoran (Landing Page)
     */
    public function home()
    {
        Reservation::autoCancelExpired();
        $popularFoods = Menu::where('category', 'food')->take(3)->get();
        $popularDrinks = Menu::where('category', 'drink')->take(3)->get();
        return view('customer.home', compact('popularFoods', 'popularDrinks'));
    }

    /**
     * Halaman Katalog Menu Makanan & Minuman
     */
    public function menu(Request $request)
    {
        Reservation::autoCancelExpired();
        $selectedDate = $request->get('date', now()->addDay()->toDateString());

        // Ambil menu dan hitung sisa kuota untuk tanggal terpilih
        $foods = Menu::where('category', 'food')->where('is_available', true)->get()->map(function ($menu) use ($selectedDate) {
            $menu->remaining_quota = $menu->getRemainingQuotaForDate($selectedDate);
            return $menu;
        });

        $drinks = Menu::where('category', 'drink')->where('is_available', true)->get()->map(function ($menu) use ($selectedDate) {
            $menu->remaining_quota = $menu->getRemainingQuotaForDate($selectedDate);
            return $menu;
        });

        return view('customer.menu', compact('foods', 'drinks', 'selectedDate'));
    }

    /**
     * Halaman Rules & Ketentuan Reservasi
     */
    public function rules()
    {
        return view('customer.rules');
    }

    /**
     * Form Pemesanan Meja Baru (Booking)
     */
    public function bookingCreate(Request $request)
    {
        Reservation::autoCancelExpired();

        $minDate = now()->addDay()->toDateString();
        $selectedDate = $request->get('date', $minDate);
        if ($selectedDate < $minDate) {
            $selectedDate = $minDate;
        }

        $selectedTime = $request->get('time', '12:00');

        // Jam operasional: 10:00 s/d 19:00 WIB (durasi makan 2 jam, tutup jam 21:00)
        $operationalHours = [
            '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00'
        ];

        // Cari meja yang terbentur di tanggal & rentang waktu 2 jam
        $bookedTableIds = $this->getBookedTableIds($selectedDate, $selectedTime);

        $tables = Table::all()->map(function ($table) use ($bookedTableIds) {
            $table->is_booked = in_array($table->id, $bookedTableIds);
            return $table;
        });

        // Ambil menu beserta sisa kuota hari itu
        $menus = Menu::where('is_available', true)->get()->map(function ($menu) use ($selectedDate) {
            $menu->remaining_quota = $menu->getRemainingQuotaForDate($selectedDate);
            return $menu;
        });

        return view('customer.booking', compact('minDate', 'selectedDate', 'selectedTime', 'operationalHours', 'tables', 'menus'));
    }

    /**
     * Cek Ketersediaan Meja & Kuota via AJAX
     */
    public function checkAvailability(Request $request)
    {
        Reservation::autoCancelExpired();

        $date = $request->get('date', now()->addDay()->toDateString());
        $time = $request->get('time', '12:00');

        $bookedTableIds = $this->getBookedTableIds($date, $time);

        $tables = Table::all()->map(function ($table) use ($bookedTableIds) {
            return [
                'id' => $table->id,
                'table_number' => $table->table_number,
                'capacity' => $table->capacity,
                'is_booked' => in_array($table->id, $bookedTableIds),
            ];
        });

        $menus = Menu::where('is_available', true)->get()->map(function ($menu) use ($date) {
            return [
                'id' => $menu->id,
                'remaining_quota' => $menu->getRemainingQuotaForDate($date),
            ];
        });

        return response()->json([
            'tables' => $tables,
            'menus' => $menus,
        ]);
    }

    /**
     * Simpan Pemesanan Reservasi Baru
     */
    public function bookingStore(Request $request)
    {
        Reservation::autoCancelExpired();

        $minDate = now()->addDay()->toDateString();

        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:255',
            'reservation_date' => 'required|date|after_or_equal:' . $minDate,
            'reservation_time' => 'required|in:10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00,18:00,19:00',
            'table_id'         => 'required|exists:tables,id',
            'guest_count'      => 'required|integer|min:1',
            'notes'            => 'nullable|string|max:500',
            'menu_quantities'  => 'nullable|array',
            'menu_quantities.*'=> 'nullable|integer|min:0',
        ], [
            'reservation_date.after_or_equal' => 'Pemesanan meja wajib dilakukan minimal H-1 sebelum hari H (' . Carbon::parse($minDate)->format('d M Y') . ').',
            'reservation_time.in' => 'Jam reservasi hanya tersedia antara 10:00 s/d 19:00 WIB sesuai jam operasional.',
        ]);

        // Cek kembali ketersediaan meja agar tidak double-booking
        $bookedTableIds = $this->getBookedTableIds($request->reservation_date, $request->reservation_time);
        if (in_array((int)$request->table_id, $bookedTableIds)) {
            return back()->withInput()->withErrors(['table_id' => 'Maaf! Meja yang Anda pilih sudah dipesan oleh tamu lain pada rentang jam tersebut. Silakan pilih meja lain atau jam lain.']);
        }

        // Cek ketersediaan kuota menu
        $menuItems = [];
        $totalAmount = 0;
        if ($request->has('menu_quantities')) {
            foreach ($request->menu_quantities as $menuId => $qty) {
                $qty = (int)$qty;
                if ($qty > 0) {
                    $menu = Menu::find($menuId);
                    if ($menu) {
                        $remaining = $menu->getRemainingQuotaForDate($request->reservation_date);
                        if ($qty > $remaining) {
                            return back()->withInput()->withErrors([
                                'menu' => "Maaf, porsi {$menu->name} hanya tersisa {$remaining} porsi untuk tanggal tersebut (Anda memesan {$qty} porsi)."
                            ]);
                        }
                        $subtotal = $menu->price * $qty;
                        $totalAmount += $subtotal;
                        $menuItems[] = [
                            'menu_id' => $menu->id,
                            'quantity' => $qty,
                            'price' => $menu->price,
                            'subtotal' => $subtotal,
                        ];
                    }
                }
            }
        }

        // 1. Simpan atau perbarui data customer
        $customer = Customer::firstOrCreate(
            ['phone' => $request->customer_phone],
            ['name' => $request->customer_name, 'email' => $request->customer_email]
        );
        $customer->update([
            'name' => $request->customer_name,
            'email' => $request->customer_email,
        ]);

        // 2. Buat reservasi dengan status PENDING dan batas waktu bayar 1 jam
        $reservation = Reservation::create([
            'customer_id'      => $customer->id,
            'table_id'         => $request->table_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'guest_count'      => $request->guest_count,
            'status'           => 'pending',
            'total_amount'     => $totalAmount,
            'payment_deadline' => now()->addHour(), // 1 jam batas konfirmasi pembayaran
            'notes'            => $request->notes,
        ]);

        // 3. Simpan detail menu yang dipesan
        foreach ($menuItems as $item) {
            $item['reservation_id'] = $reservation->id;
            ReservationMenu::create($item);
        }

        return redirect()->route('customer.booking.show', $reservation->id)
            ->with('success', 'Reservasi berhasil dibuat! Silakan lakukan konfirmasi pembayaran via WhatsApp dalam batas waktu 1 jam.');
    }

    /**
     * Halaman Detail / Invoice Reservasi & Tombol Direct WhatsApp
     */
    public function bookingShow($id)
    {
        Reservation::autoCancelExpired();

        $reservation = Reservation::with(['customer', 'table', 'reservationMenus.menu'])->findOrFail($id);

        // Buat URL Direct WhatsApp
        $waNumber = '6282254781995';
        $customerName = $reservation->customer->name ?? '-';
        $tableNumber = $reservation->table->table_number ?? '-';
        $resDate = Carbon::parse($reservation->reservation_date)->format('d M Y');
        $resTime = Carbon::parse($reservation->reservation_time)->format('H:i');
        $totalFormatted = 'Rp ' . number_format($reservation->total_amount, 0, ',', '.');

        $menuListText = '';
        if ($reservation->reservationMenus->count() > 0) {
            $menuListText = "\n*Daftar Menu Dipesan:*";
            foreach ($reservation->reservationMenus as $rm) {
                $menuListText .= "\n- {$rm->menu->name} ({$rm->quantity}x) = Rp " . number_format($rm->subtotal, 0, ',', '.');
            }
        }

        $message = "Halo Admin RestoUs, saya ingin konfirmasi reservasi dan pembayaran meja dengan rincian:"
            . "\n\n*Kode Reservasi:* #RES-" . str_pad($reservation->id, 4, '0', STR_PAD_LEFT)
            . "\n*Nama Pemesan:* " . $customerName
            . "\n*No. HP:* " . ($reservation->customer->phone ?? '-')
            . "\n*Meja:* " . $tableNumber
            . "\n*Jadwal:* " . $resDate . " pukul " . $resTime . " WIB (Maks 2 Jam)"
            . "\n*Jumlah Tamu:* " . $reservation->guest_count . " Orang"
            . $menuListText
            . "\n*Total Biaya Menu:* " . $totalFormatted
            . "\n*Batas Waktu Bayar:* 1 Jam setelah pemesanan"
            . "\n\nMohon info nomor rekening / QRIS untuk pembayaran reservasi saya. Terima kasih!";

        $waUrl = 'https://wa.me/' . $waNumber . '?text=' . urlencode($message);

        return view('customer.booking_show', compact('reservation', 'waUrl', 'waNumber'));
    }

    /**
     * Halaman Faktur / Invoice Resmi Siap Cetak dan Unduh PDF
     * 
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function invoiceDownload($id)
    {
        Reservation::autoCancelExpired();

        $reservation = Reservation::with(['customer', 'table', 'reservationMenus.menu'])->findOrFail($id);

        return view('customer.invoice', compact('reservation'));
    }

    /**
     * Cek Riwayat Reservasi Customer via Nomor Telepon
     */
    public function myReservations(Request $request)
    {
        Reservation::autoCancelExpired();

        $phone = $request->get('phone', auth()->check() ? auth()->user()->phone : null);
        $reservations = collect();

        if ($phone) {
            $customer = Customer::where('phone', $phone)->first();
            if ($customer) {
                $reservations = Reservation::with(['table', 'reservationMenus.menu'])
                    ->where('customer_id', $customer->id)
                    ->latest()
                    ->get();
            }
        }

        return view('customer.my_reservations', compact('phone', 'reservations'));
    }

    /**
     * Helper: Mendapatkan ID Meja yang sudah terpesan di tanggal & rentang waktu 2 jam
     */
    private function getBookedTableIds(string $date, string $time): array
    {
        $requestedStart = Carbon::parse($date . ' ' . $time);
        $requestedEnd = (clone $requestedStart)->addHours(2);

        // Ambil semua reservasi aktif pada tanggal yang sama
        $reservationsOnDate = Reservation::where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) {
                // Pending yang belum expired atau confirmed
                $q->where('status', 'confirmed')
                  ->orWhere('payment_deadline', '>=', now());
            })
            ->get();

        $bookedTableIds = [];

        foreach ($reservationsOnDate as $res) {
            $dateOnly = Carbon::parse($res->reservation_date)->format('Y-m-d');
            $existingStart = Carbon::parse($dateOnly . ' ' . $res->reservation_time);
            $existingEnd = (clone $existingStart)->addHours(2);

            // Cek persinggungan rentang 2 jam: StartA < EndB AND EndA > StartB
            if ($requestedStart->lt($existingEnd) && $requestedEnd->gt($existingStart)) {
                $bookedTableIds[] = $res->table_id;
            }
        }

        return array_unique($bookedTableIds);
    }
}
