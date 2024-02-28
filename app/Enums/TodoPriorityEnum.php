<?php

namespace App\Enums;

enum TodoPriorityEnum: string
{
    case HIGH = 'high';
    case NORMAL = 'normal';
    case LOW = 'low';

    public function priorities(): string
    {
        return match($this)
        {
            self::HIGH => 'High',
            self::NORMAL => 'Normal',
            self::LOW => 'Low',
        };
    }
}
