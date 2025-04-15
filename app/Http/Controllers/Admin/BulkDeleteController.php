<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\BulkDeletableTrait;

class BulkDeleteController extends Controller
{
    use BulkDeletableTrait;
}
