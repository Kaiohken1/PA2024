<?php

namespace App\Livewire;

use App\Models\Appartement;
use App\Models\Category;
use App\Models\Service;
use Livewire\Component;

class ServiceForm extends Component
{
    public $appartement;
    public $categories;
    public $services = [];
    public $selectedCategory = null;
    public $selectedServices = [];

    public function mount()
    {
        $this->appartement = Appartement::findOrFail(request()->route('id'));
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
        } else {
            $this->services = [];
        }
    }

    public function render()
    {
        return view('livewire.service-form');
    }
}
