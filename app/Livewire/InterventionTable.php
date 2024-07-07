<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Intervention;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;

class InterventionTable extends Component
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


    public function delete(Intervention $intervention) {
        $intervention->statut_id = 4;
        $intervention->save();

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

    public function exportCsv() {
        $interventions = Intervention::withTrashed()
            ->search($this->search)
            ->when($this->statut !== '', function($query) {
                $query->where('statut_id', $this->statut);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

        $timestamp = date('Y-m-d_H-i-s');
        $filename = "interventions_{$timestamp}.csv";
        $handle = fopen($filename, 'w');

        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, ['ID', 'Service', 'Statut', 'Date de Demande', 'Client', 'Prestataire', 'Date Prévue', 'Prix']);

        foreach ($interventions as $intervention) {
            fputcsv($handle, [
                $intervention->id,
                $intervention->services->getModel()->name,
                $intervention->statut->nom,
                \Carbon\Carbon::parse($intervention->created_at)->format('d/m/Y H:i:s'),
                $intervention->user->name . ' ' . $intervention->user->first_name,
                $intervention->provider ? $intervention->provider->name : 'Pas encore attribué',
                \Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y H:i:s'),
                $intervention->services->getModel()->flexPrice ? 'Variable' : $intervention->services->getModel()->price . '€',
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
        return view('livewire.intervention-table',
        [
            'interventions' => Intervention::withTrashed()
            ->search($this->search)
            ->when($this->statut !== '', function($query) {
                $query->where('statut_id', $this->statut);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage),
        ]);
    }
}
