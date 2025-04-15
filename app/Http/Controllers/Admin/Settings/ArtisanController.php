<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function optimizeClear()
    {
        Artisan::call('optimize:clear');

        return redirect()->back()->with('success', 'Optimize:Clear command executed successfully.');
    }

    public function configCache()
    {
        Artisan::call('config:cache');

        return redirect()->back()->with('success', 'Config:Cache command executed successfully.');
    }

    public function maintenanceToggle()
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');

            return redirect()->back()->with('success', 'Maintenance mode turned off.');
        } else {
            Artisan::call('down');

            return redirect()->back()->with('success', 'Maintenance mode turned on.');
        }
    }
}
