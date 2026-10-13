<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // Menampilkan daftar semua reservasi
    public function index()
    {
        $reservations = Reservation::with(['customer', 'table'])
            ->latest('reservation_date')
            ->latest('reservation_time')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    // Menampilkan form pemesanan baru
    public function create()
    {
        $tables = Table::all();
        return view('reservations.create', compact('tables'));
    }

    // Menyimpan data pemesanan baru
    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:255',
            'table_id'         => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'guest_count'      => 'required|integer|min:1',
            'notes'            => 'nullable|string',
        ]);

        // 1. Simpan atau cari data customer berdasarkan nomor telepon
        $customer = Customer::firstOrCreate(
            ['phone' => $request->customer_phone],
            ['name' => $request->customer_name, 'email' => $request->customer_email]
        );

        // Jika nama diubah, perbarui
        $customer->update([
            'name'  => $request->customer_name,
            'email' => $request->customer_email,
        ]);

        // 2. Buat reservasi baru
        Reservation::create([
            'customer_id'      => $customer->id,
            'table_id'         => $request->table_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'guest_count'      => $request->guest_count,
            'status'           => 'confirmed',
            'notes'            => $request->notes,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dibuat!');
    }

    // Menampilkan form edit reservasi
    public function edit(Reservation $reservation)
    {
        $tables = Table::all();
        $reservation->load(['customer', 'table']);
        return view('reservations.edit', compact('reservation', 'tables'));
    }

    // Memperbarui data reservasi
    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:255',
            'table_id'         => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'guest_count'      => 'required|integer|min:1',
            'status'           => 'required|in:pending,confirmed,completed,cancelled',
            'notes'            => 'nullable|string',
        ]);

        // Perbarui data customer terkait
        $reservation->customer->update([
            'name'  => $request->customer_name,
            'phone' => $request->customer_phone,
            'email' => $request->customer_email,
        ]);

        // Perbarui reservasi
        $reservation->update([
            'table_id'         => $request->table_id,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'guest_count'      => $request->guest_count,
            'status'           => $request->status,
            'notes'            => $request->notes,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Data reservasi berhasil diperbarui!');
    }

    // Menghapus reservasi
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dihapus!');
    }
}
