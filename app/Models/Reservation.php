<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    protected $fillable = [
        'customer_id',
        'table_id',
        'reservation_date',
        'reservation_time',
        'guest_count',
        'status',
        'total_amount',
        'payment_deadline',
        'notes',
    ];

    protected $casts = [
        'payment_deadline' => 'datetime',
        'reservation_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function reservationMenus(): HasMany
    {
        return $this->hasMany(ReservationMenu::class);
    }

    /**
     * Memeriksa dan membatalkan otomatis reservasi pending yang melebihi batas 1 jam.
     */
    public static function autoCancelExpired(): void
    {
        self::where('status', 'pending')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', now())
            ->update(['status' => 'cancelled']);
    }

    /**
     * Cek apakah reservasi ini sudah kadaluarsa.
     */
    public function isExpired(): bool
    {
        return $this->status === 'pending' && $this->payment_deadline && $this->payment_deadline->isPast();
    }
}
