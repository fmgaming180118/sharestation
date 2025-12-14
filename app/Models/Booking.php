<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'station_id',
        'charging_slot_id',
        'status',
        'battery_percentage',
        'start_at',
        'end_at',
        'price_per_kwh',
        'energy_kwh',
        'amount',
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'price_per_kwh' => 'float',
        'energy_kwh' => 'float',
        'amount' => 'float',
        'battery_percentage' => 'int',
    ];

    protected $attributes = [
        'status' => BookingStatus::PENDING->value,
    ];

    public static function createForUser(User $user, Station $station, array $attributes): self
    {
        $booking = new self([
            'station_id' => $station->id,
            'status' => $attributes['status'] ?? BookingStatus::PENDING,
            'start_at' => $attributes['start_at'] ?? null,
            'end_at' => $attributes['end_at'] ?? null,
            'price_per_kwh' => $attributes['price_per_kwh'] ?? $station->price_per_kwh,
            'energy_kwh' => $attributes['energy_kwh'] ?? 0,
        ]);

        $booking->amount = $booking->computeAmount();
        $booking->user()->associate($user);
        $booking->save();

        return $booking;
    }

    public function markStatus(BookingStatus $status): void
    {
        $this->update(['status' => $status]);
    }

    public function computeAmount(): float
    {
        return round(($this->energy_kwh ?? 0) * ($this->price_per_kwh ?? 0), 2);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function chargingSlot(): BelongsTo
    {
        return $this->belongsTo(ChargingSlot::class);
    }

    public function getTimeRemaining(): ?int
    {
        if (!$this->start_at) return null;
        $elapsedMinutes = $this->start_at->diffInMinutes(now());
        return max(0, 60 - $elapsedMinutes);
    }

    public function getBatteryPercentage(): int
    {
        if (!$this->start_at) return 0;
        $elapsedMinutes = $this->start_at->diffInMinutes(now());
        return min(100, (int)(($elapsedMinutes / 60) * 100));
    }
}
