<?php
namespace App\Enums;

enum MeetingType: string
{
    case REGULAR = 'Regular';
    case BOARD = 'Board';
    case EMERGENCY = 'Emergency';
    case STRATEGY = 'Strategy';
    case DEPARTMENT = 'Department';
    case PROJECT = 'Project';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get array of [value => formatted_name] pairs
     *
     * @return array
     */
    public static function toArray(): array
    {
        return array_map(
            fn($case) => [$case->value, ucfirst(strtolower(str_replace('_', ' ', $case->name)))],
            self::cases()
        );
    }
}