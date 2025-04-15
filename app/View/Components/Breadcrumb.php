<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public $title;

    public $items;

    public $modelInstance;

    public $action;

    public $resourceName;

    public function __construct($title = null, $items = [], $model = null, $action = null)
    {
        // If no model is provided, try to get it from the shared view data
        if (! $model) {
            $model = view()->shared('modelClass');
        }

        $this->modelInstance = is_string($model) ? app($model) : $model;
        $this->action = $action;
        $this->items = $items;
        $this->title = $title;

        // dd($this->modelInstance);

        $route = request()->route();
        if ($route) {
            $controller = $route->getController();
            $this->resourceName = $controller->resourceName ?? null;
        }
    }

    public function render()
    {
        return view('components.breadcrumb');
    }
}
