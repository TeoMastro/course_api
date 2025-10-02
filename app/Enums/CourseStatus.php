<?php

namespace App\Enums;

enum CourseStatus: string
{
    case PUBLISHED = 'Published';
    case PENDING = 'Pending';
    
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
