<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appartement;
use App\Models\AppartementImage;

class AppartementImages extends Component
{
    public $appartement;
    public $images;
    public $mainImages;
    public $otherImages;

    public function mount($appartementId)
    {
        $this->appartement = Appartement::find($appartementId);
        $this->loadImages();
    }

    public function loadImages()
    {
        $this->mainImages = $this->appartement->images()->where('is_main', true)->get();
        $this->otherImages = $this->appartement->images()->where('is_main', false)->get();
    }

    public function setMain($imageId)
    {
        if ($this->mainImages->count() < 4) {
            AppartementImage::where('id', $imageId)->update(['is_main' => true, 'main_order' => $this->mainImages->count() + 1]);
            $this->loadImages();
        } else {
            session()->flash('error', 'Vous pouvez définir jusqu\'à 4 images principales.');
        }
    }

    public function unsetMain($imageId)
    {
        AppartementImage::where('id', $imageId)->update(['is_main' => false, 'main_order' => false]);
        $this->loadImages();
    }

    public function render()
    {
        return view('livewire.appartement-images');
    }
}