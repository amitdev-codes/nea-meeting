<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataTable extends Component
{
    public $id;

    public $routeName;

    public $columns;

    public $class;

    public $columnsDefinition;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $routeName, $columns, $columnsDefinition = [], $class = '')
    {
        $this->id = $id;
        $this->routeName = $routeName;
        $this->columns = $columns;
        $this->columnsDefinition = $columnsDefinition;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.data-table');
    }
}
