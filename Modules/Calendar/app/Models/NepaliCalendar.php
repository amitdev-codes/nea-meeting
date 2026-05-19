<?php

namespace Modules\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Calendar\Database\Factories\NepaliCalendarFactory;

class NepaliCalendar extends Model
{
    use HasFactory;
    protected $table = 'nepali_calendar';

    protected $fillable = [
        'bs_year',
        'month',
        'days',
        'start_date',
        'status',
    ];
        protected $casts = [
        'bs_year' => 'integer',
        'month' => 'integer',
        'days' => 'integer',
      'start_date' => 'date:Y-m-d',
    ];



}
