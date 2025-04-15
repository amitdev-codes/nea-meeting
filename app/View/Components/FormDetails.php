<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormDetails extends Component
{
    public $data;
    public $expanded;
    public $uniqueId;

    public function __construct($data = [], $expanded = false, $uniqueId = null)
    {
        $this->data = $data;
        $this->expanded = $expanded;
        $this->uniqueId = $uniqueId ?? uniqid();
    }

    public function render()
    {
        return view('components.form-details', [
            'data' => $this->data,
            'expanded' => $this->expanded,
            'uniqueId' => $this->uniqueId,
        ]);
    }
}