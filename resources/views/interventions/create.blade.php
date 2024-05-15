<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h1 class="text-3xl font-bold mb-4">{{ __('Réservez un service') }}</h1>
        @if ($errors->any())
            <div class="text-red-500">
                <ul>
                    @foreach ($errors->keys() as $errorKey)
                        @foreach ($errors->get($errorKey) as $errorMessage)
                            <li>{{ $errorMessage }}</li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        @endif
        @if(!$services->isEmpty())
            <form id="intervention-form" method="POST"
                action="{{ route('interventions.store', ['id' => $selectedAppartement]) }}">
                @csrf

                <input type="hidden" name="appartement_id" value="{{ $selectedAppartement->id }}">

                <livewire:service-form />

                <x-primary-button class="mt-4">{{ __('Demander une prestation') }}</x-primary-button>
            </form>
        @else
            <p>Aucun service n'est disponible actuellement</p>
        @endif
    </div>
</x-app-layout>
