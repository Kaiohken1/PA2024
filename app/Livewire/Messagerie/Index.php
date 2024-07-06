<?php

namespace App\Livewire\Messagerie;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $layout;
    public function render()
    {
        if (Auth::user()->provider) {
            $this->layout = 'layouts.provider';
        } else {
            $this->layout = 'layouts.admin'; 
        }

        return view('livewire.messagerie.index')
        ->layout($this->layout);

    }
}
