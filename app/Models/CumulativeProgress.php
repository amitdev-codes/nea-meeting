<?php

namespace App\Models;

use Modules\Master\Models\FiscalYear;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CumulativeProgress extends Model
{
    protected $table='cumulative_progress';
    protected $fillable = [
        'project_start_date', 'project_end_date', 'total_estimated_expenditure', 'total_given_expenditure',
        'total_budget', 'total_disbursed','total_group_formed_target'
    ];

    protected $casts = [
        'project_start_date' => 'date',
        'project_end_date' => 'date',
    ];

    public function fiscal_year(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'fiscal_year_id', 'id');
    }

}
