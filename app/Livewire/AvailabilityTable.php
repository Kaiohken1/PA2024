<?php

namespace App\Livewire;

use App\Models\Intervention;
use App\Models\InterventionEstimation;
use Livewire\Component;
use App\Models\Provider;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class AvailabilityTable extends Component
{
    use WithPagination;

    #[Url()]
    public $perPage = 5;

    #[Url(history:true)]
    public $search = '';

    #[Url(history:true)]
    public $statut = '';

    #[Url(history:true)]
    public $sortBy = 'created_at';

    #[Url(history:true)]
    public $sortDir = 'DESC';

    public $interventionId;



    public function updatedSearch() {
        $this->resetPage();
    }

    public function setSortBy($sortBy) {

        if($this->sortBy === $sortBy) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortBy;
        $this->sortDir = 'DESC';
    }

    public function render()
    {
        return view('livewire.availability-table',
        [
            'estimations' => InterventionEstimation::search($this->search)
            ->when($this->statut !== '', function($query) {
                $query->where('statut_id', $this->statut);
            })
            ->where('intervention_id', $this->interventionId)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage),

            'intervention' => Intervention::findOrFail($this->interventionId),
        ]);
    }
}
