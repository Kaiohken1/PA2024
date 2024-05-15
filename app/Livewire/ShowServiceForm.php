<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;

class ShowServiceForm extends Component
{
    public $service_id;
    public $service;
    public $price;
    public $description;
    public $flexPrice;
    public $documents;
    
    public function mount()
    {
        $this->service_id = Service::first()->id;
    }

    public function render()
    {
        if ($this->service = Service::find($this->service_id)) {
            $this->flexPrice = $this->service->flexPrice;
            $this->price = $this->service->price;
            $this->description = $this->service->description;
            $this->documents = $this->service->documents;
        }
        
        return view('livewire.show-service-form', ['services' => $this->service]);
    }
}