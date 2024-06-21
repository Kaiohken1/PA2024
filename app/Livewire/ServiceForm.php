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
    public $services = [];
    public $selectedCategory = null;
    public $selectedService = null;

    public function mount()
    {
        $user = Auth::user();
        $roleIds = $user->roles->pluck('id')->toArray();

        $this->appartement = Appartement::findOrFail(request()->route('id'));
        $this->categories = Category::whereHas('services', function($query) use ($roleIds) {
            $query->where('active_flag', 1)
                ->whereIn('role_id', $roleIds);
        })->get();
        $this->updateServices();
    }

    public function updateServices()
    {
        $user = Auth::user();
        $roleIds = $user->roles->pluck('id')->toArray();
        
        if ($this->selectedCategory) {
            $this->services = Service::whereIn('role_id', $roleIds)
            ->where('category_id', $this->selectedCategory)
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
        $this->dispatch('servicesUpdated', hasService: $this->selectedService);
    }

    public function render()
    {
        return view('livewire.service-form');
    }
}
