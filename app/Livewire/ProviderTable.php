<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Provider;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ProviderTable extends Component
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


    public function delete(Provider $provider) {
        $provider->delete();
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
        return view('livewire.provider-table',
        [
            'providers' => provider::search($this->search)
            ->when($this->statut !== '', function($query) {
                $query->where('statut', $this->statut);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage),
        ]);
    }
}
