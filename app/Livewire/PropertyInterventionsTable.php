<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appartement;
use App\Models\Intervention;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;

class PropertyInterventionsTable extends Component
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

    public $propertyId;

    public function mount() {
        $this->propertyId = request()->route('id');
    }


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
        return view('livewire.property-interventions-table',
        [
            'interventions' => Intervention::where('appartement_id', $this->propertyId)
            ->where('statut_id', 3)
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage),
            'appartement' => Appartement::findOrFail($this->propertyId)
        ]);
    }
}
