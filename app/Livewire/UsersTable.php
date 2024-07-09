<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Intervention;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class UsersTable extends Component
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
        $provider->statut = "Supprimé";
        $provider->save();

        foreach($provider->interventions->where('statut_id', '!=', 5)->where('statut_id', '!=', 3) as $intervention) {
            $intervention->provider_id = NULL;
            $intervention->price = NULL;
            $intervention->commission = NULL;
            $intervention->statut_id = 1;
            $intervention->save();
            foreach($intervention->estimations->where('provider_id', $provider->id) as $estimation) {
                $estimation->delete();
            }
        }

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
        return view('livewire.users-table',
        [
            'users' => User::search($this->search)
            ->when($this->statut !== '', function($query) {
                $query->whereHas('roles', function($roleQuery) {
                    $roleQuery->where('role_id', $this->statut);
                });
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage),
        ]);
    }
}
