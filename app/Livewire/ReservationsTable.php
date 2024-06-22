<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Fermeture;
use App\Models\Reservation;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;

class ReservationsTable extends Component
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


    public function delete(Reservation $reservation) {
        $reservation->delete();
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
        $reservations = Reservation::search($this->search)
            ->when($this->statut !== '', function($query) {
                $query->where('statut_id', $this->statut);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();

        $timestamp = date('Y-m-d_H-i-s');
        $filename = "reservations__{$reservations->first()->appartement->name}_{$timestamp}.csv";
        $handle = fopen($filename, 'w');

        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, ['ID', 'Appartement', 'Prix', 'Date d\'arrivé', 'Date de départ']);

        foreach ($reservations as $reservation) {
            fputcsv($handle, [
                $reservation->id,
                $reservation->prix,
                \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y'),
                \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y'),
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
        return view('livewire.reservations-table',
        [
            'reservations' => Reservation::search($this->search)
            ->when($this->statut !== '', function($query) {
                $query->where('statut_id', $this->statut);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage),
        ]);
    }
}
