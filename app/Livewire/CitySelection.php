<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Provider;
use App\Models\ProviderCitySelection;
use Illuminate\Support\Facades\DB;

class CitySelection extends Component
{
    public $selectedCities = [];
    public $provider;
    public $cities = [];

    public function mount()
    {
        $this->provider = Provider::findOrFail(Auth::user()->provider->id);
        $this->selectedCities = $this->provider->selectedCities->pluck('city')->toArray();
        $this->cities = DB::table('appartements')->select('city')->distinct()->pluck('city')->toArray();
    }

    public function updatedSelectedCities()
    {
        $this->syncSelectedCities();
    }

    protected function syncSelectedCities()
    {
        $this->provider->selectedCities()->delete();

        foreach ($this->selectedCities as $city) {
            $this->provider->selectedCities()->create(['city' => $city]);
        }
    }

    public function render()
    {
        return view('livewire.city-selection', [
            'cities' => $this->cities,
        ])
        ->layout('layouts.provider');
    }
}