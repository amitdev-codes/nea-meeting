<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Forms\Models\Form;
use Modules\Groups\Models\Group;
use Modules\Master\Models\Sector;
use Modules\Master\Models\District;
use App\Http\Controllers\Controller;
use Modules\Master\Models\SubSector;
use Modules\Master\Models\LocalLevel;
use Modules\Groups\Models\GroupMember;
use Modules\Master\Models\CropVariety;
use Modules\Lmbis\Models\LmbisActivity;
use Modules\Master\Models\SubComponent;

class DropdownController extends Controller
{
    /**
     * Returns a list of districts based on the given province ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistricts(Request $request)
    {
        $districts = District::where('province_id', $request->id)->get(['id', 'name']);
        return response()->json($districts);
    }

    /**
     * Returns a list of local levels based on the given district ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocalLevels(Request $request)
    {
        $localLevels = LocalLevel::where('district_id', $request->id)->get(['id', 'name']);
        return response()->json($localLevels);
    }
    /**
     * Retrieves the number of wards for a given local level ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing the number of wards.
     * If the local level is not found, returns 0 wards.
     */

    public function getLocalLevelWards(Request $request)
    {
        $localLevel = LocalLevel::where('id', $request->id)->first(['id', 'wards']);
        if ($localLevel) {
            return response()->json(['wards' => $localLevel->wards]);
        }
        return response()->json(['wards' => 0]);
    }
    /**
     * Retrieves a list of subcomponents for a given component ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing a list of subcomponents, each with their ID and name.
     */
    public function getSubcomponents(Request $request)
    {
        $subcomponents = SubComponent::where('component_id', $request->id)->get(['id', 'name']);
        return response()->json($subcomponents);
    }
    /**
     * Retrieves a list of sub-sectors for a given sector ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing a list of sub-sectors, each with their ID and name.
     */
    public function getSubSectors(Request $request)
    {
        $subSectors = SubSector::where('sector_id', $request->id)->get(['id', 'name']);
        return response()->json($subSectors);
    }

    /**
     * Retrieves a list of LMBIS activities for a given component ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing a list of LMBIS activities, each with their ID, name and code.
     */
    public function getLmbisActivity(Request $request)
    {
        $lmbisActivities = LmbisActivity::where('component_id', $request->id)->get(['id', 'lmbis_activity_name','code']);
        return response()->json($lmbisActivities);
    }
    /**
     * Retrieves a list of LMBIS activities for a given sub-component ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing a list of LMBIS activities, each with their ID, name and code.
     */
    public function getLmbisActivityFromSubcomponent(Request $request)
    {
        $lmbisActivities = LmbisActivity::where('sub_component_id', $request->id)->get(['id', 'lmbis_activity_name','code']);
        return response()->json($lmbisActivities);
    }
    /**
     * Retrieves a list of forms for a given LMBIS activity ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing a list of forms, each with their ID, name and name_np.
     * If the LMBIS activity is not found, returns a 404 response.
     * If the LMBIS activity has no forms, returns an empty array.
     */
    public function getForms(Request $request)
    {
        $lmbisActivity = LmbisActivity::findOrFail($request->id);
        $formIds =  $lmbisActivity->forms;
     
    
        // Handle both single value and array cases
        if (is_null($formIds)) {
            return response()->json([]);
        }
    
        if (is_string($formIds)) {
            $formIds = json_decode($formIds, true); // Decode JSON string to array
        } elseif (!is_array($formIds)) {
            $formIds = [$formIds]; // Convert single value to array if not already an array
        }
    
        // If $formIds is still not an array or empty after decoding, return empty response
        if (!is_array($formIds) || empty($formIds)) {
            return response()->json([]);
        }
    

    
        $forms = Form::whereIn('id', $formIds)
            ->get(['id', 'name', 'name_np'])
            ->map(function ($form) {
                return [
                    'id' => $form->id,
                    'name' => $form->name . ' (' . $form->name_np . ')', // Combine name and name_np
                ];
            });
    
        return response()->json($forms);
    }

    /**
     * Retrieves a list of beneficiaries for a given group ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing a list of beneficiaries, each with their ID and name.
     * If the group ID is not found, returns a 404 response.
     * If the group has no beneficiaries, returns an empty array.
     */
    public function getBeneficiary(Request $request)
    {
        $group_id=$request->group_id??$request->id;
        $beneficiaries = GroupMember::where('group_id', $group_id)->get(['id', 'name']);
        return response()->json($beneficiaries);
    }
    /**
     * Retrieves a list of crop varieties for a given crop ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing a list of crop varieties, each with their ID, name and name_np.
     * If the crop ID is not found, returns a 400 response with a message 'Crop ID is required'.
     */
    public function getCropVarieties(Request $request)
    {
        $cropId = $request->crop_id ?? $request->id;
        if (!$cropId) {
            return response()->json(['message' => 'Crop ID is required'], 400);
        }
        $cropVarieties = CropVariety::where('crop_id', $cropId)->get(['id', 'name','name_np']);
        return response()->json($cropVarieties);
    }
    
    /**
     * Retrieves a list of groups for a given local level ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing a list of groups, each with their ID and name.
     * If the local level ID is not found, returns a 404 response.
     * If the local level has no groups, returns an empty array.
     */
    public function getGroups(Request $request)
    {
        $groups = Group::where('localLevel_id', $request->id)->get(['id', 'name']);
        return response()->json($groups);
    }
    /**
     * Retrieves a list of local levels for a given sector ID.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Returns a JSON response containing a list of local levels, each with their ID, name and name_np.
     * If the sector ID is not found, returns a 404 response.
     * If the sector has no local levels, returns an empty array.
     */
    public function getLocalLevelsBySector(Request $request)
    {
        // Get the IDs of local levels associated with groups in the given sector
        $ids = Group::where('sector_id', $request->id)->pluck('localLevel_id');
        $localLevels = LocalLevel::whereIn('id', $ids)->get(['id', 'name','name_np']);
        return response()->json($localLevels);
    }
    public function getSectors(Request $request)
    {
        $ids = Group::where('localLevel_id', $request->id)->pluck('sector_id');
        $sectors = Sector::whereIn('id', $ids)->get(['id', 'name','name_np']);
        return response()->json($sectors);
    }
    public function getGroupsBySector(Request $request)
    {
        $groups = Group::where('sector_id', $request->id)->get(['id', 'name']);
        return response()->json($groups);
    }

}
