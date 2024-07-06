<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProvidersInvoicesTable extends Component
{
    use WithPagination;

    #[Url()]
    public $perPage = 5;

    #[Url(history:true)]
    public $search = '';

    #[Url(history:true)]
    public $period = '';

    #[Url(history:true)]
    public $sortBy = 'created_at';

    #[Url(history:true)]
    public $sortDir = 'DESC';

    public $availablePeriods = [];

    public function mount()
    {
        Carbon::setLocale('fr'); 
        $this->periods();
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

    public function periods()
    {
        $periods = Invoice::select(
            DB::raw('DATE_FORMAT(created_at, "%m-%Y") as period')
        )
        ->groupBy('period')
        ->orderBy('period', 'desc')
        ->pluck('period')
        ->toArray();

        $this->availablePeriods = array_map(function($period) {
            $date = Carbon::createFromFormat('m-Y', $period);
            return [
                'value' => $period,
                'text' => $date->translatedFormat('F Y')
            ];
        }, $periods);
    }

    public function render()
    {        
        return view('livewire.reservations-invoices-table',
        [
            'invoices' => Invoice::search($this->search)
            ->where('role', "Prestataire")
            ->where('user_id', Auth::user()->id)
            ->when($this->period !== '', function($query) {
                [$month, $year] = explode('-', $this->period);
                $query->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage),
        ]);
    }
}
