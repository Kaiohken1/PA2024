<x-app-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold ">Vos demandes de prestations</h1>
        <p class="text-lg">Gérez vos demande de prestations</p>

        @if (session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600" role="alert">
                {{ session('success') }}
            </div>
                @elseif (session('error'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600"
                role="alert">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-md rounded-lg p-6">
                @if(!$interventions->isEmpty())
                    @foreach ($interventions as $intervention)
                        <div class="flex items-center border-b pb-4">

                            <div class="ml-4">
                                <h3 class="text-xl font-medium">{{ $intervention->service->name }}</h3>
                                <span>Intervention #{{ $intervention->id }}</span>
                                <p>Date d'intervention: {{ \Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y') }} | Heure: {{ \Carbon\Carbon::parse($intervention->planned_date)->format('H:i') }}</p>
                                <p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $intervention->statut_id == 2 || $intervention->statut_id == 5 ? 'bg-green-100 text-green-800' : ($intervention->statut_id == 1 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($intervention->statut->nom) }}
                                    </span>
                                </p>
                            </div>
                            <div class="ml-auto">
                                <a href="{{ route('interventions.clientShow', ['id' => $intervention->id]) }}">
                                    <button class="text-yellow-500 font-semibold text-xl">Voir</button>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No service requests available.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
