<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Intervention;

class InterventionDetails extends Component
{
    public $intervention;

    protected $listeners = ['loadIntervention'];

    public function loadIntervention($id)
    {
        $this->intervention = Intervention::findOrFail($id);

    }

    public function render()
    {
        return view('livewire.intervention-details');
    }
}