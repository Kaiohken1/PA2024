<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appartement;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;

class PropertyTable extends Component
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


    public function delete(Appartement $appartement) {
        $appartement->delete();
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

    public function exportCsv() {
        $appartements = Appartement::withTrashed()
            ->search($this->search)
            ->when($this->statut !== '', function($query) {
                $query->where('statut_id', $this->statut);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

        $timestamp = date('Y-m-d_H-i-s');
        $filename = "logement__{$timestamp}.csv";
        $handle = fopen($filename, 'w');


        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, ['ID', 'Nom', 'Adresse', 'Ville', 'Proprietaire', 'Prix par nuit', 'Statut']);

        foreach ($appartements as $appartement) {
            $userName = $appartement->user->name . ' ' . $appartement->user->first_name;
            fputcsv($handle, [
                $appartement->id,
                $appartement->name,
                $appartement->address,
                $appartement->city,
                $userName,
                $appartement->price,
                $appartement->statut->nom
            ]);
        }

        fclose($handle);

        return Response::download($filename, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ])->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.property-table',
        [
            'appartements' => Appartement::withTrashed()
            ->search($this->search)
            ->when($this->statut !== '', function($query) {
                $query->where('statut_id', $this->statut);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage),
        ]);
    }
}
