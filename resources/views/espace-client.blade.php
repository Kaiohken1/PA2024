<x-app-layout>
    <x-slot name="header">
        
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mon espace client') }}
        </h2>

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

    <div class="py-28 bg-gray-100">
        <div class="container mx-auto flex justify-center gap-16">
            <a href="{{route('reservation')}}">
                <div class="card bg-white w-96 shadow-xl rounded-lg overflow-hidden hover:bg-gray-100">
                    <div class="card-body p-6">
                        <h2 class="card-title text-2xl font-bold mb-4">{{ __('Mes réservations') }}</h2>
                        <p class="text-gray-600">{{__('Accèdez à vos réservations en cours et passées ainsi qu\'aux documents associés')}}</p>
                    </div>
                </div>
            </a>
            <a href="{{route('interventions.dashboard')}}">
                <div class="card bg-white w-96 shadow-xl rounded-lg overflow-hidden hover:bg-gray-100">
                    <div class="card-body p-6">
                        <h2 class="card-title text-2xl font-bold mb-4">{{ __('Mes demandes de prestations') }}</h2>
                        <p class="text-gray-600">{{__('Accèdez à vos demandes de prestations ainsi qu\'aux documents associés')}}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>