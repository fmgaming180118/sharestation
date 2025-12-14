<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'google_id',
        'avatar',
        'password',
        'is_host',
        'role',
        'phone',
        'phone_number',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_host' => 'bool',
        ];
    }

    public static function loginOrRegisterGoogle($socialUser): self
    {
        $user = static::where('google_id', $socialUser->getId())->first()
            ?? static::where('email', $socialUser->getEmail())->first();

        if ($user) {
            if (!$user->google_id) {
                $user->forceFill([
                    'google_id' => $socialUser->getId(),
                ])->save();
            }

            return $user;
        }

        return static::create([
            'name' => $socialUser->getName() ?: $socialUser->getEmail(),
            'email' => $socialUser->getEmail(),
            'google_id' => $socialUser->getId(),
            'avatar' => null,
            'password' => null,
            'is_host' => false,
        ]);
    }

    public function stations(): HasMany
    {
        return $this->hasMany(Station::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
