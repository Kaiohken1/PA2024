<?php

namespace App\Livewire;

use App\Models\Absence;
use Livewire\Component;
use App\Models\Provider;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use App\Models\InterventionEvent;
use Illuminate\Support\Facades\Auth;

class ProviderCalendar extends Component
{
    public $interventions = [];
    public $absences = [];
    public $provider_id;
    public $isAdmin;
    public function render(Request $request)
    {        
        $interventionsQuery = InterventionEvent::query();
    
        if ($this->provider_id !== null) {
            $interventionsQuery->where('provider_id', $this->provider_id);
        }
    
        $absences = Absence::query()->where('provider_id', $this->provider_id)->get();
    
        $formattedAbsences = [];
        foreach ($absences as $absence) {
            $formattedAbsences[] = [
                'title' => $absence->title,
                'start' => date('Y-m-d', strtotime($absence->start)),
                'end' => date('Y-m-d', strtotime($absence->end)),
                'color' => '#FF0000',
            ];
        }
    
        $this->interventions = json_encode($interventionsQuery->get());
        $this->absences = json_encode($formattedAbsences);

        $this->isAdmin = Auth::user()->isAdmin();
    
        return view('livewire.provider-calendar');
    }
}
