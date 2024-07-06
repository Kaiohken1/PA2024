<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Appartement;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\Tag;

class SearchProperty extends Component
{
    use WithPagination;

    #[Url()]
    public $city;

    #[Url()]
    public $start_time;

    #[Url()]
    public $end_time;

    #[Url()]
    public $guestCount;
    
    public $appartements = [];
    public $pagination = 8;
    public $hasMorePages = true;
    public $sortType = 'latest';
    public $selectedTags = []; 
    public $showTagModal = false; 

    public function mount()
    {
        if ($this->city || $this->start_time || $this->end_time || $this->guestCount) {
            $this->search();
        }
    }

    public function updatedCity()
    {
        $this->search();
    }

    public function updatedStartTime()
    {
        $this->search();
    }

    public function updatedEndTime()
    {
        $this->search();
    }

    public function updatedGuestCount()
    {
        $this->search();
    }

    public function updatedSortType()
    {
        $this->search();
    }

    public function updatedSelectedTags()
    {
        $this->search();
    }

    public function loadMore()
    {
        $this->pagination += 8;
        $this->search();
    }

    public function search()
    {
        if (empty($this->city) && empty($this->start_time) && empty($this->end_time) && empty($this->guestCount)) {
            return redirect()->to('/');
        }

        $start_time = date('Y-m-d', strtotime($this->start_time));
        $end_time = date('Y-m-d', strtotime($this->end_time));

        $appartementsQuery = Appartement::where('city', 'like', '%' . $this->city . '%')
            ->where('active_flag', 1)
            ->withCount('avis')
            ->withAvg('avis', 'rating_cleanness')
            ->withAvg('avis', 'rating_price_quality')
            ->withAvg('avis', 'rating_location')
            ->withAvg('avis', 'rating_communication')
            ->when($this->guestCount, function ($query, $guestCount) {
                return $query->where('guestCount', '>=', $guestCount);
            })
            ->when($this->selectedTags, function ($query) {
                return $query->whereHas('tags', function ($q) {
                    $q->whereIn('name', $this->selectedTags);
                });
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
            });

        switch ($this->sortType) {
            case 'price_asc':
                $appartementsQuery->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $appartementsQuery->orderBy('price', 'desc');
                break;

            case 'surface_asc':
                $appartementsQuery->orderBy('surface', 'asc');
                break;

            case 'surface_desc':
                $appartementsQuery->orderBy('surface', 'desc');
                break;

            case 'guest_count_asc':
                $appartementsQuery->orderBy('guestCount', 'asc');
                break;

            case 'guest_count_desc':
                $appartementsQuery->orderBy('guestCount', 'desc');
                break;

            case 'avis_asc':
                $appartementsQuery->orderBy('avis_count', 'asc');
                break;

            case 'avis_desc':
                $appartementsQuery->orderBy('avis_count', 'desc');
                break;

            default:
                $appartementsQuery->orderBy('created_at', 'desc');
                break;
        }

        $appartementsPaginated = $appartementsQuery->paginate($this->pagination);

        $this->appartements = $appartementsPaginated->items();
        $this->hasMorePages = $appartementsPaginated->hasMorePages();

        $this->appartements = collect($this->appartements)->each(function ($appartement) {
            $appartement->overall_rating = ($appartement->avis_avg_rating_cleanness + $appartement->avis_avg_rating_price_quality + $appartement->avis_avg_rating_location + $appartement->avis_avg_rating_communication) / 4;
            return $appartement;
        });

        $this->dispatch('search');

        $this->showTagModal = false; 
    }

    public function render()
    {
        return view('livewire.search-property', [
            'tags' => Tag::all(),
        ]);
    }

    public function toggleTag($tagName)
    {
        if (in_array($tagName, $this->selectedTags)) {
            $this->selectedTags = array_diff($this->selectedTags, [$tagName]);
        } else {
            $this->selectedTags[] = $tagName;
        }
        $this->search();
    }
}
