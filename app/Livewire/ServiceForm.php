<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;
use App\Models\Category;
use App\Models\Appartement;
use Illuminate\Support\Facades\Auth;

class ServiceForm extends Component
{
    public $appartement;
    public $categories;
    public $id;
    public $services = [];
    public $selectedCategory = null;
    public $selectedService = null;

    public function mount()
    {
        $this->appartement = Appartement::withTrashed()->findOrFail($this->id);
        $this->categories = Category::whereHas('services', function($query) {
            $query->where('active_flag', 1);
        })->get();
        $this->updateServices();
    }

    public function updateServices()
    {
        if ($this->selectedCategory) {
            $this->services = Service::where('category_id', $this->selectedCategory)
                ->where('active_flag', 1)
                ->get();
            $this->selectedService = null;
        } else {
            $this->services = [];
            $this->selectedService = null;
        }
    }

    public function updateCat()
    {
        $this->selectedService = null;
        $this->updateServices();
        $this->dispatch('servicesUpdated', hasService: $this->selectedService);

    }

    public function updatedSelectedService()
    {
        $this->dispatch('servicesUpdated', hasService: $this->selectedService, range :$this->services->where('id', $this->selectedService)->first()->hasRange);
        $this->dispatch('selected-service', serviceId : $this->selectedService);

    }

    public function render()
    {
        return view('livewire.service-form');
    }
}
