<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class DynamicInput extends Component
{
    public Collection $inputs;

    public function addInput()
    {
        $this->inputs->push(['text' => '']);
    }

    public function removeInput($key)
    {
        $inputsArray = $this->inputs->toArray();
        unset($inputsArray[$key]);
        $this->inputs = collect(array_values($inputsArray));
    }

    public function submitForm()
    {
        $this->emit('submitForm', $this->inputs);
    }


    public function mount()
    {
        $this->fill([
            'inputs' => collect([['text' => '']]),
        ]);
    }

    public function render()
    {
        return view('livewire.dynamic-input');
    }
}
