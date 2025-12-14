<?php

namespace App\Enums;

enum BookingStatus: int
{
    case PENDING = 1;
    case CHARGING = 2;
    case COMPLETED = 3;
    case CANCELLED = 4;

    public function isActive(): bool
    {
        return in_array($this, [self::PENDING, self::CHARGING], true);
    }
}
