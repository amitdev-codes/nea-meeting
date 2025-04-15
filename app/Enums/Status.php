<?php
namespace App\Enums;

enum Status: string
{
    case Pending = 'Pending';
    case Verified = 'Verified';
    case Approved = 'Approved'; // Fixed typo and consistency
    case Rejected = 'Rejected';


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