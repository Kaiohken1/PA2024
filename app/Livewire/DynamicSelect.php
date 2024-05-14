<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;

class DynamicSelect extends Component
{
    public Collection $inputs;
    

    public function addInput()
    {
        $this->inputs->push(['select' => '']);
    }
    
    public function removeInput($key)
    {
        $inputsArray = $this->inputs->toArray();
        unset($inputsArray[$key]);
        $this->inputs = collect(array_values($inputsArray));

    }

    public function mount()
    {
        $this->fill([
            'inputs' => collect([['select' => '']]),
        ]);
    }

    public function render()
    {
        return view('livewire.dynamic-select');
    }
}
