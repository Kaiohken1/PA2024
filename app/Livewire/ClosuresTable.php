<?php

namespace App\Livewire;

use App\Models\Fermeture;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ClosuresTable extends Component
{
    use WithPagination;

    #[Url()]
    public $perPage = 5;

    #[Url(history:true)]
    public $search = '';

    #[Url(history:true)]
    public $sortBy = 'start';

    #[Url(history:true)]
    public $sortDir = 'ASC';

    public $appartementId;


    public function delete(Fermeture $intervention) {
        $intervention->delete();
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
        return view('livewire.closures-table',
        [
            'fermetures' => Fermeture::search($this->search)
                ->where('appartement_id', $this->appartementId)
                ->orderBy($this->sortBy, $this->sortDir)
                ->paginate($this->perPage),

        ]);
    }
}
