<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Appartement;

use Livewire\Attributes\Url;
use function Laravel\Prompts\search;

class SearchProperty extends Component
{
    #[Url()]
    public $city;

    #[Url()]
    public $start_time;

    #[Url()]
    public $end_time;

    #[Url()]
    public $guestCount;
    
    
    public $appartements = [];

    public function mount()
    {
        if ($this->city || $this->start_time || $this->end_time || $this->guestCount) {
            $this->search();
        }
    }
    public function search()
    {
        $start_time = date('Y-m-d', strtotime($this->start_time));
        $end_time = date('Y-m-d', strtotime($this->end_time));
        $this->appartements = Appartement::where('city', 'like', '%' . $this->city . '%')
            ->where('active_flag', 1)
            ->withCount('avis')
            ->withAvg('avis', 'rating_cleanness')
            ->withAvg('avis', 'rating_price_quality')
            ->withAvg('avis', 'rating_location')
            ->withAvg('avis', 'rating_communication')
            
            ->when($this->guestCount, function ($query, $guestCount) {
                return $query->where('guestCount', '>=', $guestCount);
            })

            ->whereDoesntHave('reservations', function ($query) use ($start_time, $end_time) {
                $query->where(function ($query) use ($start_time, $end_time) {
                    $query->whereBetween('start_time', [$start_time, $end_time])
                        ->orWhereBetween('end_time', [$start_time, $end_time])
                        ->orWhere(function ($query) use ($start_time, $end_time) {
                            $query->where('start_time', '<', $start_time)
                                ->where('end_time', '>', $end_time);
                        });
                });
            })
            ->whereDoesntHave('fermetures', function ($query) use ($start_time, $end_time) {
                $query->where(function ($query) use ($start_time, $end_time) {
                    $query->whereBetween('start', [$start_time, $end_time])
                        ->orWhereBetween('end', [$start_time, $end_time])
                        ->orWhere(function ($query) use ($start_time, $end_time) {
                            $query->where('start', '<', $start_time)
                                ->where('end', '>', $end_time);
                        });
                });
            })
            ->orderByDesc('created_at')
            ->get();



        $this->appartements = $this->appartements->each(function ($appartement) {
            $appartement->overall_rating = ($appartement->avis_avg_rating_cleanness + $appartement->avis_avg_rating_price_quality  + $appartement->avis_avg_rating_location  + $appartement->avis_avg_rating_communication) / 4;
            return $appartement;
        });

        $this->dispatch('search');
    }
    public function render()
    {
        return view('livewire.search-property');
    }
}
