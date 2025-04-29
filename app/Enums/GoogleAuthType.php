<?php
namespace App\Enums;

enum GoogleAuthType: string
{
    case SERVICE_ACCOUNT = 'SERVICE_ACCOUNT';
    case OAUTH = 'OAUTH';


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