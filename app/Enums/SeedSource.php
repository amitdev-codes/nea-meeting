<?php
namespace App\Enums;

enum SeedSource: string
{
    case NARC = 'NARC';
    case LOCAL_AGROVET = 'LOCAL AGROVET';
    case LOCAL_COOPERATIVE = 'LOCAL CO-OPERATIVE'; // Fixed typo and consistency
    case NSC = 'NSC';
    case GOVERNMENT_FARM = 'GOVERNMENT FARM';
    case OTHERS = 'OTHERS';


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