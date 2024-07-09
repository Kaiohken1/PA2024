<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tag;

class Estimation extends Component
{
    public $surface;
    public $guestCount;
    public $roomCount;
    public $tag_id = [];
    public $aspect_rating;
    public $location_rating;
    public $property_type = 1;
    public $priceEstimation;

    public $tags;

    public function mount()
    {
        $this->tags = Tag::all();
    }

    public function render()
    {
        return view('livewire.estimation');
    }

    public function updated()
    {
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        $validatedData = $this->validate([
            'surface' => 'required|numeric|min:1',
            'guestCount' => 'required|numeric|min:1',
            'roomCount' => 'required|numeric|min:1',
            'tag_id' => 'array',
            'aspect_rating' => 'required|numeric|min:1',
            'location_rating' => 'required|numeric|min:1',
            'property_type' => 'required|numeric|min:1'
        ]);

        $basePrice = $validatedData['surface'] * (1 + $validatedData['location_rating'] / 5) * (1 + $validatedData['aspect_rating'] / 10) * $validatedData['property_type'];
        $priceEstimation = $basePrice * (1 + $validatedData['roomCount'] / 100) * (1 + $validatedData['guestCount'] / 100);

        if (!empty($validatedData['tag_id'])) {
            $selectedTags = Tag::whereIn('id', $validatedData['tag_id'])->get();
            foreach ($selectedTags as $tag) {
                $priceEstimation *= $tag->valorisation_coeff;
            }
        }

        $this->priceEstimation = (int)$priceEstimation;
    }
}
