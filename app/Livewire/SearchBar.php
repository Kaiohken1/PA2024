<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Provider;
use Livewire\Attributes\Url;

class SearchBar extends Component
{
    #[Url]
    public $search = "";

    public function render()
    {
        $results = [];

        if(strlen($this->search) >= 1) {
            $results = Provider::where('name', 'like', '%'.$this->search.'%')
                                ->orwhere('name', 'like', '%'.$this->search.'%')
                                ->limit(7)->get();
        }

        return view('livewire.search-bar', [
            'providers' => $results
        ]);
    }
}
