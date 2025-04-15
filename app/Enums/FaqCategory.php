<?php
namespace App\Enums;

enum FaqCategory: string
{
    case GENERAL = 'GENERAL';
    case SUbMISSIONS = 'SUbMISSIONS';
    case STORIES = 'STORIES';
    case SUPPORT = 'SUPPORT';



    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [$case->value, ucfirst($case->name)], self::cases());
    }
}