<?php

namespace App\Livewire\Sliders;

use App\Models\Slider;
use Livewire\Component;

class SliderStatus extends Component
{
    public $slider_id;

    public $slider_status;

    public function mount($slider_id, $slider_status)
    {
        $this->slider_id = $slider_id;
        $this->slider_status = (bool)$slider_status;
    }

    public function render()
    {
        return view('livewire.sliders.slider-status');
    }

    public function updateStatus()
    {
        try{
            $slider = Slider::find($this->slider_id);
            $slider->slider_status = $this->slider_status;
            $slider->save();
            $this->dispatch('livewireToastr', status: 'success', message: 'Slider updated successfully');
        } catch (\Exception $e) {
            $this->dispatch('livewireToastr', status: 'error', message: 'Slider update failed!');
        }
    }
}
