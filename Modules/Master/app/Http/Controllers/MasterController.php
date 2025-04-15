<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Master\Models\District;
use App\Http\Controllers\Controller;
use Modules\Master\Models\LocalLevel;

class MasterController extends Controller
{
    public function getDistricts(Request $request)
    {
        $provinceIds = $request->input('province_ids', []);
        $districts = District::whereIn('province_id', $provinceIds)
            ->get()
            ->map(function ($district) {
                return [
                    'id' => $district->id,
                    'text' => $district->name . ' - ' . $district->name_np
                ];
            });

        return response()->json(['districts' => $districts]);
    }

    public function getLocalLevels(Request $request)
    {
        $districtIds = $request->input('district_ids', []);
        $localLevels = LocalLevel::whereIn('district_id', $districtIds)
            ->get()
            ->map(function ($localLevel) {
                return [
                    'id' => $localLevel->id,
                    'text' => $localLevel->name . ' - ' . $localLevel->name_np
                ];
            });

        return response()->json(['local_levels' => $localLevels]);
    }
}
