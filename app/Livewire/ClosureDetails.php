<?php

namespace App\Livewire;

use App\Models\Fermeture;
use Livewire\Component;

class ClosureDetails extends Component
{
    public $fermeture;

    protected $listeners = ['loadClosure', 'deleteClosure'];

    public function loadClosure($id)
    {
        $this->fermeture = Fermeture::findOrFail($id);
    }

    public function deleteClosure($id)
    {
        $this->fermeture = Fermeture::findOrFail($id);

        $this->fermeture->delete();

        return redirect(request()->header('Referer'));


    }

    public function render()
    {
        return view('livewire.closure-details');
    }
}
