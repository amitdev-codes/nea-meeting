<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActionButtons extends Component
{
    public $id;

    public $editRoute;

    public $deleteRoute;

    /**
     * Create a new component instance.
     */
    public function __construct($id, $editRoute, $deleteRoute)
    {
        $this->id = $id;
        $this->editRoute = $editRoute;
        $this->deleteRoute = $deleteRoute;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.action-buttons');
    }
}
