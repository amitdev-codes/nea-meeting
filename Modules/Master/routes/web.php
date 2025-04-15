<?php

use Illuminate\Support\Facades\Route;
use Modules\Master\Http\Controllers\CropController;
use Modules\Master\Http\Controllers\AssetController;
use Modules\Master\Http\Controllers\BreedController;
use Modules\Master\Http\Controllers\CasteController;
use Modules\Master\Http\Controllers\GenderController;
use Modules\Master\Http\Controllers\MasterController;
use Modules\Master\Http\Controllers\SectorController;
use Modules\Master\Http\Controllers\ClusterController;
use Modules\Master\Http\Controllers\DistrictController;
use Modules\Master\Http\Controllers\DropdownController;
use Modules\Master\Http\Controllers\ProvinceController;
use Modules\Master\Http\Controllers\ComponentController;
use Modules\Master\Http\Controllers\LiveStockController;
use Modules\Master\Http\Controllers\SubSectorController;
use Modules\Master\Http\Controllers\FiscalYearController;
use Modules\Master\Http\Controllers\LengthUnitController;
use Modules\Master\Http\Controllers\LocalLevelController;
use Modules\Master\Http\Controllers\ClusterTypeController;
use Modules\Master\Http\Controllers\CropVarietyController;
use Modules\Master\Http\Controllers\DesignationController;
use Modules\Master\Http\Controllers\OrganizationController;
use Modules\Master\Http\Controllers\SubComponentController;
use Modules\Master\Http\Controllers\BreedCategoryController;
use Modules\Master\Http\Controllers\InfrastructureController;
use Modules\Master\Http\Controllers\LiveStockBreedController;
use Modules\Master\Http\Controllers\StarterCategoryController;
use Modules\Master\Http\Controllers\ExpenditureCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'master', 'middleware' => ['locale','auth']], function () {
    Route::resource('provinces', ProvinceController::class)->names('admin.provinces');
    Route::post('provinces/import', [ProvinceController::class,'import'])->name('admin.provinces.import');

    Route::resource('districts', DistrictController::class)->names('admin.districts');
    Route::post('districts/import', [DistrictController::class, 'import'])->name('admin.districts.import');

    Route::resource('local-levels', LocalLevelController::class)->names('admin.local-levels');
    Route::post('local-levels/import', [LocalLevelController::class,'import'])->name('admin.local-levels.import');

    Route::resource('length-units', LengthUnitController::class)->names('admin.length-units');
    Route::post('length-units/import', [LengthUnitController::class,'import'])->name('admin.length-units.import');

    Route::resource('genders', GenderController::class)->names('admin.genders');
    Route::post('genders/import', [GenderController::class,'import'])->name('admin.genders.import');

    Route::resource('fiscal-years', FiscalYearController::class)->names('admin.fiscal-years');
    Route::post('fiscal-years/import', [FiscalYearController::class,'import'])->name('admin.fiscal-years.import');

    Route::resource('components', ComponentController::class)->names('admin.components');
    Route::post('components/import', [ComponentController::class,'import'])->name('admin.components.import');

    Route::resource('sub-components', SubComponentController::class)->names('admin.sub-components');
    Route::post('sub-components/import', [SubComponentController::class,'import'])->name('admin.sub-components.import');

    Route::resource('crops', CropController::class)->names('admin.crops');
    Route::post('crops/import', [CropController::class,'import'])->name('admin.crops.import');

    Route::resource('livestocks', LiveStockController::class)->names('admin.livestocks');
    Route::post('livestocks/import', [LiveStockController::class,'import'])->name('admin.livestocks.import');

    Route::resource('livestock-breeds', LiveStockBreedController::class)->names('admin.livestock-breeds');
    Route::post('livestock-breeds/import', [LiveStockBreedController::class,'import'])->name('admin.livestock-breeds.import');

    Route::resource('crop-varieties', CropVarietyController::class)->names('admin.crop-varieties');
    Route::post('crop-varieties/import', [CropVarietyController::class,'import'])->name('admin.crop-varieties.import');

    Route::resource('cluster-types', ClusterTypeController::class)->names('admin.cluster-types');
    Route::post('cluster-types/import', [ClusterTypeController::class,'import'])->name('admin.cluster-types.import');

    Route::resource('clusters', ClusterController::class)->names('admin.clusters');
    Route::post('clusters/import', [ClusterController::class,'import'])->name('admin.clusters.import');

    Route::resource('designations', DesignationController::class)->names('admin.designations');
    Route::post('designations/import', [DesignationController::class,'import'])->name('admin.designations.import');

    Route::resource('expenditure-categories', ExpenditureCategoryController::class)->names('admin.expenditure-categories');
    Route::post('expenditure-categories/import', [ExpenditureCategoryController::class,'import'])->name('admin.expenditure-categories.import');

    Route::resource('castes', CasteController::class)->names('admin.castes');
    Route::post('castes/import', [CasteController::class,'import'])->name('admin.castes.import');

    Route::resource('sectors', SectorController::class)->names('admin.sectors');
    Route::post('sectors/import', [SectorController::class,'import'])->name('admin.sectors.import');

    Route::resource('sub-sectors', SubSectorController::class)->names('admin.sub-sectors');
    Route::post('sub-sectors/import', [SubSectorController::class,'import'])->name('admin.sub-sectors.import');

    Route::resource('starter-categories', StarterCategoryController::class)->names('admin.starter-categories');
    Route::post('starter-categories/import', [StarterCategoryController::class,'import'])->name('admin.starter-categories.import');



    Route::resource('assets', AssetController::class)->names('admin.assets');
    Route::resource('infrastructures', InfrastructureController::class)->names('admin.infrastructures');
    Route::resource('organizations', OrganizationController::class)->names('admin.organizations');


    // Route::post('get-districts', [MasterController::class, 'getDistricts'])->name('get.districts');
    // Route::post('get-local-levels', [MasterController::class, 'getLocalLevels'])->name('get.local.levels');

    //inline edit check
    Route::patch('provinces/{province}/inline-edit', [ProvinceController::class, 'inlineEdit'])
    ->name('admin.provinces.inline-edit')
    ->middleware('auth');

    Route::get('get-districts', [DropdownController::class, 'getDistricts'])->name('get.districts');
    Route::get('get-local-levels', [DropdownController::class, 'getLocalLevels'])->name('get.local-levels');
    Route::get('get-wards', [DropdownController::class, 'getLocalLevelWards'])->name('get.local-level-wards');
    Route::get('get-subcomponents', [DropdownController::class, 'getSubcomponents'])->name('get.subcomponents');
    Route::get('get-subSectors', [DropdownController::class, 'getSubSectors'])->name('get.subSectors');
    //forms
    Route::get('get-lmbisActivities', [DropdownController::class, 'getLmbisActivity'])->name('get.lmbisActivity');

    Route::get('get-forms', [DropdownController::class, 'getForms'])->name('get.forms');

    Route::get('get-beneficiaries', [DropdownController::class, 'getBeneficiary'])->name('get.beneficiaries');
    Route::get('get-cropVarieties', [DropdownController::class, 'getCropVarieties'])->name('get.crop_varieties');
    Route::get('get-groups', [DropdownController::class, 'getGroups'])->name('get.groups');

    //entry module form on the basis of group
    Route::get('get-lmbisActivitiesFromSubComponents', [DropdownController::class, 'getLmbisActivityFromSubcomponent'])->name('get.getLmbisActivityFromSubcomponent');
    Route::get('get-localLevelsBySector', [DropdownController::class, 'getLocalLevelsBySector'])->name('get.localLevelsBySector');
    //get sectors on the base of Rm
    Route::get('get-sectors', [DropdownController::class, 'getSectors'])->name('get.sectors');
    Route::get('get-groupsBySectors', [DropdownController::class, 'getGroupsBySector'])->name('get.groupsBySectors');

});
