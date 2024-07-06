<div class="bg-white shadow-lg rounded-lg p-6">
    <div class="stat-title text-gray-700">Propositions d'Interventions</div>
    <div class="stat-value text-amber-600 text-3xl font-bold">{{ $interventions->count() }}</div>
    <ul class="list-disc list-inside text-gray-600">
        @foreach ($interventions as $intervention)
            <li>{{ \Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y H:i')}} - {{$intervention->appartement->address}}</li>
        @endforeach
        @if($interventions->count() !=0)...@endif
    </ul>
    <a href="{{ route('providers.proposals') }}" class="text-blue-500 mt-4 block">Voir toutes les propositions</a>
</div>
