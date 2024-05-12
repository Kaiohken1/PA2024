<?php

namespace App\Livewire;

use App\Models\Appartement;
use App\Models\Service;
use Livewire\Component;

class ServiceForm extends Component
{
    public $services;
    public $appartement;
    public $selectedServices = [];


    public function mount()
    {
        $this->appartement = Appartement::findOrfail(request()->route('id'));
        $this->services = Service::All()->where('active_flag', 1);
    }

    public function render()
    {
        return view('livewire.service-form');
    }
}
