<?php

namespace App\Http\Controllers;

use App\DataTables\SlidersDataTable;
use App\Models\Slider;
use App\Http\Requests\StoreSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Traits\HandlesExceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    use HandlesExceptions;

    public function index( SlidersDataTable $dataTable)
    {
        abort_if(Gate::denies('view sliders'), 403, 'You do not have access to this page.');
        return $dataTable->render('pages.sliders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('create sliders'), 403, 'You do not have access to this page.');
        return view('pages.sliders.slider-form');
    }

    public function show(Slider $slider)
    {
        abort_if(Gate::denies('view sliders'), 403, 'You do not have access to this page.');
        return view('pages.sliders.show', compact('slider'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSliderRequest $request)
    {
        $slider_validated = $request->validated();
        $slider_validated['slider_status'] = $request->input('slider_status') ?? 0;

        $slider = Slider::create($slider_validated);

        if ($request->has('images')) {
            foreach ($request->input('images', []) as $file) {
                $slider->addMedia(Storage::path('temp/dropzone/' . $file))->toMediaCollection('images');
            }
        }

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        abort_if(Gate::denies('edit sliders'), 403, 'You do not have access to this page.');
        return view('pages.sliders.slider-form', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        $slider_validated = $request->validated();
        $slider_validated['slider_status'] = $request->input('slider_status') ?? 0;
        $slider->update($slider_validated);

        if ($request->has('images')) {
            foreach ($request->input('images', []) as $file) {
                $slider->addMedia(Storage::path('temp/dropzone/' . $file))->toMediaCollection('images');
            }
        }

        return redirect()
                ->route('admin.sliders.index')
                ->with('success', 'Sponser updated successfully.');
    }

    public function destroy(Request $request, Slider $slider)
    {
        if ($request->ajax()) {
            abort_if(Gate::denies('delete sliders'), 403, 'You do not have access to this page.');
            try {
                $slider->delete();
                return response()->json(['status' => 'success', 'message' => 'Slider deleted successfully.'], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Failed to delete slider. Please try again.'], 500);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => "Invalid ajax request!"]);
        }
    }

    public function updateOrder(Request $request)
    {
        if ($request->ajax()) {
            foreach ($request->order as $index => $data) {
                Slider::where('id', $data['id'])
                        ->update(['order' => $data['position']]);
            }
            return response()->json(['status' => 'success', 'message' => "Slider order updated successfully"]);
        } else {
            return response()->json(['status' => 'error', 'message' => "Invalid ajax request!"]);
        }
    }
}
