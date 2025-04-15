<?php

namespace Modules\Landingpage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Landingpage\Database\Factories\FaqFactory;

class Faq extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'answer',
        'category',
        'is_active',
        'order_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
        // Scope for active FAQs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered FAQs
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_id', 'asc')->orderBy('created_at', 'asc');
    }
}
