<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'operational_hours',
        'is_open',
        'amenities',
        'image',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'is_open' => 'bool',
        'latitude' => 'float',
        'longitude' => 'float',
        'amenities' => 'array', // Cast JSON to array
    ];

    public static function createForUser(User $user, array $attributes): self
    {
        return $user->stations()->create([
            'name' => $attributes['name'],
            'address' => $attributes['address'] ?? null,
            'latitude' => $attributes['latitude'],
            'longitude' => $attributes['longitude'],
            'price_per_kwh' => $attributes['price_per_kwh'],
            'power_kw' => $attributes['power_kw'] ?? 0,
            'is_active' => $attributes['is_active'] ?? true,
        ]);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function ports(): HasMany
    {
        return $this->hasMany(Port::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function availablePorts(): int
    {
        return $this->ports()->where('status', 'available')->count();
    }

    public function averageRating(): float
    {
        return (float) $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in kilometers
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth radius in kilometers

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get distance from a specific location
     */
    public function distanceFrom(float $latitude, float $longitude): float
    {
        return self::calculateDistance($latitude, $longitude, $this->latitude, $this->longitude);
    }
}
