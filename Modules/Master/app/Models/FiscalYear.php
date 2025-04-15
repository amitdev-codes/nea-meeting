<?php

namespace Modules\Master\Models;

use App\Traits\HasStatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Master\Database\Factories\FiscalYearFactory;

class FiscalYear extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'mst_fiscal_years';

    protected $fillable = [
        'code',
        'date_from_bs',
        'date_to_bs',
        'date_from_ad',
        'date_to_ad',
        'is_previous',
        'is_current',
        'is_next',
    ];

    // protected static function newFactory(): FiscalYearFactory
    // {
    //     // return FiscalYearFactory::new();
    // }
}
