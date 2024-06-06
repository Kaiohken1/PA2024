@php
use App\Models\Intervention;
$interventions = Intervention::where('provider_id', $provider->id)
                        ->get();
@endphp

<div class="bg-white shadow-lg rounded-lg p-6">
    <div class="stat-title text-gray-700">Interventions en cours</div>
    <div class="stat-value text-amber-600 text-3xl font-bold">{{ $interventions->count() }}</div>
    <a href="{{ route('provider.interventionIndex') }}" class="text-blue-500 mt-4 block">Voir toutes les interventions</a>
</div>
