<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportFailure extends Model
{
    protected $fillable = [
        'import_type',
        'row_number', 
        'original_data', 
        'error_message', 
        'file_name',
        'imported_at'
    ];

    protected $casts = [
        'original_data' => 'array',
        'imported_at' => 'datetime'
    ];
}
