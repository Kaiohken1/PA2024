<x-provider-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl leading-tight text-gray-800">
            {{ __('Espace Prestataire') }} {{ $provider->name }}
        </h2>

        @if (session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600" role="alert">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600" role="alert">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>
    
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- {{dd($proposals)}} --}}
            @include('provider.partials.proposals', ['interventions' => $proposals])
            @include('provider.partials.calandar')
            @include('provider.partials.absences', ['absences' => $absences])

            
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="stat-title text-gray-700">Total Gains</div>
                <div class="stat-value text-amber-600 text-3xl font-bold">{{ number_format($totalGains, 2) }} €</div>
                <div class="stat-desc text-gray-500">Cumulatif des gains</div>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="stat-title text-gray-700">Gains Mensuels</div>
                <div class="stat-value text-amber-500 text-3xl font-bold">{{ number_format($monthlyGains, 2) }} €</div>
                <div class="stat-desc text-gray-500">Gains pour {{ $currentMonthYear }}</div>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="stat-title text-gray-700">Total Interventions</div>
                <div class="stat-value text-amber-600 text-3xl font-bold">{{ $provider->interventions()->where('statut_id', 3)->count() }}</div>
                <div class="stat-desc text-gray-500">Interventions effectuées</div>
            </div>
        </div>
    </div>
</x-provider-layout>
