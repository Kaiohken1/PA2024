<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appartement;
use Livewire\WithPagination;


class AllProperty extends Component
{
    use WithPagination;

    public $appartements = [];

    public $pagination = 8;

    public function mount()
    {
        $this->search();
    }

    public function loadMore() {
        $this->pagination += 8;
        $this->search();
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <h2 class="text-3xl font-extrabold mt-8 mb-4 text-center">Tous les appartements</h2>
            <div class="grid grid-cols-4 mx-auto sm:p-8 w-lvh bg-white shadow sm:rounded-lg">
                @for($i = 0; $i < 4; $i++)
                <div class="skeleton h-32 w-32"></div>
                @endfor
            </div>
        </div>
        HTML;
    }

    public function search()
    {
        $this->appartements = Appartement::where('active_flag', 1)
            ->withCount('avis')
            ->withAvg('avis', 'rating_cleanness')
            ->withAvg('avis', 'rating_price_quality')
            ->withAvg('avis', 'rating_location')
            ->withAvg('avis', 'rating_communication')
            ->orderby('created_at','DESC')
            ->paginate($this->pagination)
            ->each(function ($appartement) {
                $appartement->overall_rating = (
                    $appartement->avis_avg_rating_cleanness +
                    $appartement->avis_avg_rating_price_quality +
                    $appartement->avis_avg_rating_location +
                    $appartement->avis_avg_rating_communication
                ) / 4;
                return $appartement;
            });
            
    }

    public function render()
    {
        return view('livewire.all-property', [
            'appartements' => $this->appartements,
        ]);
    }
}
