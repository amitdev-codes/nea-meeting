<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SSBeneficiariesForm extends Model
{
    use HasFactory;
    
    protected $table = 's_s_beneficiaries_forms';
    
    protected $fillable = ['name', 'code', 'description', 'status'];
    
    protected $casts = [];
}