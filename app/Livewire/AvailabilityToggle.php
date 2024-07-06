<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Provider;
use Illuminate\Support\Facades\Auth;

class AvailabilityToggle extends Component
{
    public $isAvailable;
    public $provider;

    public function mount()
    {
        $this->provider = Provider::findOrFail(Auth::user()->provider->id);
        $this->isAvailable = $this->provider->availability;
    }

    public function toggleAvailability()
    {
        $this->provider->availability = !$this->isAvailable;
        $this->provider->save();

        $this->isAvailable = $this->provider->availability;
    }


    public function render()
    {
        return view('livewire.availability-toggle');
    }
}
