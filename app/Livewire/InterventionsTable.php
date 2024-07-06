<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Provider;
use App\Models\Intervention;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;

class InterventionsTable extends Component
{
    use WithPagination;

    #[Url()]
    public $perPage = 5;

    #[Url(history:true)]
    public $search = '';

    #[Url(history:true)]
    public $sortBy = 'created_at';

    #[Url(history:true)]
    public $sortDir = 'DESC';

    #[Url(history:true)]
    public $hasEstimate = '';

    public $provider;
    public $showHidden = false;
    public $selectedCities = [];

    public function mount()
    {
        $this->provider = Provider::findOrFail(Auth::user()->provider->id);
        $this->selectedCities = $this->provider->selectedCities->pluck('city')->toArray();
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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function setSortBy($sortBy)
    {
        if ($this->sortBy === $sortBy) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortBy;
        $this->sortDir = 'DESC';
    }

    public function getInterventionsProperty()
    {
        $providerId = $this->provider->id;
        $query = Intervention::search($this->search)
            ->when($this->hasEstimate !== '', function($query) {
                if ($this->hasEstimate === '1') {
                    $query->whereHas('estimations', function ($query) {
                        $query->where('provider_id', $this->provider->id);
                    });
                } elseif ($this->hasEstimate === '0') {
                    $query->whereDoesntHave('estimations', function ($query) {
                        $query->where('provider_id', $this->provider->id);
                    });
                }
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->where('service_id', $this->provider->services->first()->id)
            ->where('statut_id', 1)
            ->where(function($query) use ($providerId) {
                $query->where('provider_id', NULL)
              ->orWhere('provider_id', $providerId);
            })
            ->when(!empty($this->selectedCities), function($query) {
                $query->whereHas('appartement', function($query) {
                    $query->whereIn('city', $this->selectedCities);
                });
            });

        if (!$this->showHidden) {
            $query->whereDoesntHave('hidden', function ($query) {
                $query->where('provider_id', $this->provider->id);
            });
        } else {
            $query->whereHas('hidden', function ($query) {
                $query->where('provider_id', $this->provider->id);
            });
        }

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.interventions-table', [
            'interventions' => $this->interventions,
        ]);
    }

    public function delete(Intervention $intervention)
    {
        $intervention->delete();
    }
}
