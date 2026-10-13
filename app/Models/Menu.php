<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'daily_quota',
        'is_available',
        'image_url',
    ];

    public function reservationMenus(): HasMany
    {
        return $this->hasMany(ReservationMenu::class);
    }

    /**
     * Hitung sisa kuota menu untuk tanggal tertentu (memperhitungkan status pending dan confirmed).
     */
    public function getRemainingQuotaForDate(string $date): int
    {
        // Hitung kuota yang sudah terpakai di tanggal tersebut dari reservasi aktif (pending belum expired atau confirmed)
        $orderedCount = ReservationMenu::where('menu_id', $this->id)
            ->whereHas('reservation', function ($query) use ($date) {
                $query->where('reservation_date', $date)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->where(function ($q) {
                        // Jika pending, pastikan belum expired (deadline > now())
                        $q->where('status', 'confirmed')
                          ->orWhere('payment_deadline', '>=', now());
                    });
            })
            ->sum('quantity');

        $remaining = $this->daily_quota - $orderedCount;
        return max(0, $remaining);
    }
}
