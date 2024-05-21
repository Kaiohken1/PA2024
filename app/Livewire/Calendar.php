<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Fermeture;
use Illuminate\Support\Arr;

class Calendar extends Component
{

    public $fermetures = [];
    public function eventChange($fermetures)
    {
        $e = Fermeture::find($fermetures['id']);
        $e->start = $fermetures['start'];
        if(Arr::exists($fermetures, 'end')) {
            $e->end = $fermetures['end'];
        }
        $e->save();
    }

    

    public function render()
    {

        $this->fermetures = json_encode(Fermeture::all());
    //    dd($this->fermetures);


        
        return view('livewire.calendar');
    }



   
}
