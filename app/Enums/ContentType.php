<?php
namespace App\Enums;

enum ContentType: string
{
    case SUCCESS_STORY = 'Success Story';
    case LESSON_LEARNED = 'Lesson Learned';
    // case IMAGE = 'image';
    // case TESTIMONIAL = 'testimonial';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [$case->value, ucfirst($case->name)], self::cases());
    }
}