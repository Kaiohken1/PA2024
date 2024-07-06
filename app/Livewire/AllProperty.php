<?php

namespace App\Livewire;

use App\Models\Tag;
use Livewire\Component;
use App\Models\Appartement;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class AllProperty extends Component
{
    use WithPagination;

    public $appartements = [];
    public $pagination = 8;
    public $hasMorePages = true;

    #[Url()]
    public $sortType = 'latest';

    public $selectedTags = []; 

    public function mount()
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
        $appartementsQuery = Appartement::where('active_flag', 1)
            ->withCount('avis')
            ->withAvg('avis', 'rating_cleanness')
            ->withAvg('avis', 'rating_price_quality')
            ->withAvg('avis', 'rating_location')
            ->withAvg('avis', 'rating_communication')
            ->when($this->selectedTags, function ($query) {
                return $query->whereHas('tags', function ($q) {
                    $q->whereIn('name', $this->selectedTags);
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

        $appartementsQuery = $appartementsQuery->paginate($this->pagination);

        $this->appartements = $appartementsQuery->items();
        $this->hasMorePages = $appartementsQuery->hasMorePages();

        foreach ($this->appartements as $appartement) {
            $appartement->overall_rating = (
                ($appartement->avis_avg_rating_cleanness ?? 0) +
                ($appartement->avis_avg_rating_price_quality ?? 0) +
                ($appartement->avis_avg_rating_location ?? 0) +
                ($appartement->avis_avg_rating_communication ?? 0)
            ) / 4;
        }
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

    public function render()
    {
        return view('livewire.all-property', [
            'appartements' => $this->appartements,
            'hasMorePages' => $this->hasMorePages,
            'tags' => Tag::all(),
        ]);
    }
}
