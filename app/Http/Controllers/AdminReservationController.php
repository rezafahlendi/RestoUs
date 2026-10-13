<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    /**
     * Dasbor Manajemen Seluruh Reservasi Masuk
     */
    public function reservations(Request $request)
    {
        // Otomatis batalkan reservasi pending yang melebihi batas 1 jam
        Reservation::autoCancelExpired();

        $statusFilter = $request->get('status');

        $query = Reservation::with(['customer', 'table', 'reservationMenus.menu'])
            ->latest('reservation_date')
            ->latest('reservation_time');

        if ($statusFilter && in_array($statusFilter, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $query->where('status', $statusFilter);
        }

        $reservations = $query->paginate(15);

        // Ringkasan statistik cepat
        $totalPending = Reservation::where('status', 'pending')->count();
        $totalConfirmed = Reservation::where('status', 'confirmed')->count();
        $totalCompleted = Reservation::where('status', 'completed')->count();
        $totalCancelled = Reservation::where('status', 'cancelled')->count();

        return view('admin.reservations.index', compact('reservations', 'statusFilter', 'totalPending', 'totalConfirmed', 'totalCompleted', 'totalCancelled'));
    }

    /**
     * Ubah Status Reservasi (Konfirmasi, Selesai, Batalkan)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $request->status]);

        $messages = [
            'confirmed' => 'Reservasi #' . $reservation->id . ' BERHASIL DIKONFIRMASI (Pembayaran terverifikasi)! Meja resmi terkunci.',
            'completed' => 'Reservasi #' . $reservation->id . ' ditandai SELESAI.',
            'cancelled' => 'Reservasi #' . $reservation->id . ' DIBATALKAN. Meja dan kuota menu telah dibebaskan kembali.',
        ];

        return back()->with('success', $messages[$request->status] ?? 'Status reservasi berhasil diperbarui.');
    }

    /**
     * Kelola Menu & Kuota Harian Masing-Masing Menu
     */
    public function menus()
    {
        $foods = Menu::where('category', 'food')->get();
        $drinks = Menu::where('category', 'drink')->get();

        return view('admin.menus.index', compact('foods', 'drinks'));
    }

    /**
     * Perbarui Kuota / Harga / Ketersediaan Menu
     */
    public function updateMenu(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|integer|min:0',
            'daily_quota' => 'required|integer|min:0',
            'is_available' => 'required|boolean',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'price' => $request->price,
            'daily_quota' => $request->daily_quota,
            'is_available' => $request->is_available,
        ]);

        return back()->with('success', 'Menu ' . $menu->name . ' berhasil diperbarui!');
    }

    /**
     * Daftar Meja Restoran
     */
    public function tables()
    {
        $tables = Table::all();
        return view('admin.tables.index', compact('tables'));
    }
}
