<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Provider;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use App\Models\InterventionEvent;

class ProviderCalendar extends Component
{
    public $interventions = [];
    public $provider_id;
    
    public function render(Request $request)
    {        

        $interventionsQuery = InterventionEvent::query();

        if ($this->provider_id !== null) {
            $interventionsQuery->where('provider_id', $this->provider_id);
        }

        $this->interventions = json_encode($interventionsQuery->get());

        return view('livewire.provider-calendar');
    }
}
