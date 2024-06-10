<?php
namespace App\Livewire;


use Livewire\Component;
use App\Models\Provider;
use App\Models\Intervention;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class InterventionsTable extends Component
{
    use WithPagination;

    public $provider;
    public $showHidden = false;

    public function mount()
    {
        $this->provider = Provider::findOrFail(Auth::user()->provider->id);
    }

    public function hideIntervention($interventionId)
    {
        $this->provider->hidden()->attach($interventionId);
        $this->resetPage();
    }

    public function showIntervention($interventionId)
    {
        $this->provider->hidden()->detach($interventionId);
        $this->resetPage(); 
    }

    public function toggleShowHidden()
    {
        $this->showHidden = !$this->showHidden;
        $this->resetPage();
    }

    public function getInterventionsProperty()
    {
        $query = Intervention::query()
            ->where('service_id', $this->provider->services->first()->id)
            ->where('statut_id', 1)
            ->latest();

        if (!$this->showHidden) {
            $query->whereDoesntHave('hidden', function ($query) {
                $query->where('provider_id', $this->provider->id);
            });
        } else {
            $query->whereHas('hidden', function ($query) {
                $query->where('provider_id', $this->provider->id);
            });
        }

        return $query->paginate(15);
    }

    public function render()
    {
        return view('livewire.interventions-table', [
            'interventions' => $this->interventions,
        ]);
    }
}