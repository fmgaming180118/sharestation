<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'name',
        'type',
        'power_kw',
        'price_per_kwh',
        'status',
    ];

    protected $casts = [
        'power_kw' => 'integer',
        'price_per_kwh' => 'float',
    ];

    /**
     * Get the station that owns the port
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Get transactions for this port
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Check if port is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Mark port as busy
     */
    public function markBusy(): void
    {
        $this->update(['status' => 'busy']);
    }

    /**
     * Mark port as available
     */
    public function markAvailable(): void
    {
        $this->update(['status' => 'available']);
    }
}
