<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Provider;

class SelectProvider extends Component
{
    public $serviceId;
    public $selectedProvider = null;
    public $date;

    #[On('selected-service')] 
    public function getService($serviceId)
    {
        $this->serviceId = $serviceId;
        $this->selectedProvider = null;
        $this->loadProviders();
    }

    public function updatedDate()
    {
        $this->loadProviders();
    }

    public function loadProviders()
    {
        $providers = collect();
        if ($this->serviceId !== null && $this->date !== null) {
            $formattedDate = date('Y-m-d H:i:s', strtotime($this->date));
            
            $providers = Provider::whereHas('services', function ($query) {
                $query->where('service_id', $this->serviceId);
            })->whereDoesntHave('intervention_events', function ($query) use ($formattedDate) {
                $query->where(function ($query) use ($formattedDate) {
                    $query->where('start', '<=', $formattedDate)
                          ->where('end', '>=', $formattedDate);
                });
            })->get();
        }

        return $providers;
    }

    public function render()
    {
        return view('livewire.select-provider', [
            'providers' => $this->loadProviders()
        ]);
    }
}
