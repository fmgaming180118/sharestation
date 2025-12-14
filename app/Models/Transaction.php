<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'station_id',
        'port_id',
        'date',
        'start_time',
        'end_time',
        'duration_minutes',
        'total_kwh',
        'total_price',
        'payment_status',
        'confirmation_code',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer',
        'total_kwh' => 'float',
        'total_price' => 'float',
    ];

    /**
     * Boot method to auto-generate codes
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $transaction->transaction_code = static::generateTransactionCode();
            }
            if (empty($transaction->confirmation_code)) {
                $transaction->confirmation_code = static::generateConfirmationCode();
            }
        });
    }

    /**
     * Generate unique transaction code
     */
    public static function generateTransactionCode(): string
    {
        do {
            $code = 'TRF' . now()->format('Ymd') . rand(10, 99);
        } while (static::where('transaction_code', $code)->exists());

        return $code;
    }

    /**
     * Generate unique confirmation code
     */
    public static function generateConfirmationCode(): string
    {
        return Str::random(12);
    }

    /**
     * Get the user (driver) for this transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the station for this transaction
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Get the port used for this transaction
     */
    public function port(): BelongsTo
    {
        return $this->belongsTo(Port::class);
    }

    /**
     * Get the review for this transaction
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if transaction is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid(): void
    {
        $this->update(['payment_status' => 'paid']);
    }

    /**
     * Cancel transaction
     */
    public function cancel(): void
    {
        $this->update(['payment_status' => 'cancelled']);
    }
}
