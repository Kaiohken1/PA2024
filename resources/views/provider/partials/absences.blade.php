<div class="bg-white shadow-lg rounded-lg p-6">
    <div class="stat-title text-gray-700">Absences</div>
    <div class="stat-value text-amber-600 text-3xl font-bold">{{  App\Models\Absence::where('provider_id', $provider->id)->count() }}</div>
    <ul class="list-disc list-inside text-gray-600">
        @foreach ($absences as $absence)
            <li>Du {{ \Carbon\Carbon::parse($absence->start)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($absence->end)->format('d/m/Y') }}</li>
        @endforeach
        @if($absences->count() != 0)...@endif
    </ul>
    <a href="{{ route('provider.availability') }}" class="text-blue-500 mt-4 block">Voir toutes les absences</a>
</div>
