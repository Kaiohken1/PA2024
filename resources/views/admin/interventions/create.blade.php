<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
<x-admin-layout>
    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h1 class="text-3xl font-bold mb-4 text-white">{{ __('Réserver un service') }}</h1>
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
                        <form id="intervention-form" method="POST" action="{{ route('admin.interventions.store', ['id' => $selectedAppartement]) }}">
                            @csrf
    
                            <input type="hidden" name="appartement_id" value="{{ $selectedAppartement->id }}">
                            @if($reservation !== NULL)<input type="hidden" name="reservation_id" value="{{ $reservation->id }}">@endif
    
                            <livewire:service-form :id="$selectedAppartement->id"/>
    
                            <div id="additional-fields" style="display: none;">
                                {{-- <div>
                                    <x-input-label for="planned_date" :value="__('Date prévue')" class="text-white"/>
                                    <input type="text" id="planned_date" name="planned_date" placeholder="Sélectionnez une date" class="input input-bordered input-warning w-full max-w-xs">
                                    <x-input-error class="mt-2 text-red-500" :messages="$errors->get('planned_date')" />
                                </div> --}}
    
                                {{-- <div>
                                    <x-input-label for="max_end_date" :value="__('Date de fin limite (facultatif)')" class="text-white"/>
                                    <input type="text" id="max_end_date" name="max_end_date" placeholder="Sélectionnez une date" class="input input-bordered input-warning w-full max-w-xs" disabled>
                                    <x-input-error class="mt-2 text-red-500" :messages="$errors->get('max_end_date')" />
                                </div> --}}

                                <livewire:select-provider :start_time="$start_time" :end_time="$end_time">
    
                                <div>
                                    <p class="text-white mt-5">{{ __('Autre informations à faire savoir au prestataire ') }}</p>
                                    <x-input-label for="information" :value="__('Essayez d\'exprimer au mieux votre besoin afin que nous puissons vous fournir une prestation adaptée')" class="text-white"/>
                                    <textarea name="information" class="textarea textarea-warning w-full"></textarea>
                                </div>
                                <button class="mt-4 btn btn-warning">{{ __('Demander une prestation') }}</button>
                            </div>
    
                        </form>
                    @else
                        <p class="text-white">Aucun service n'est disponible actuellement</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    

    <script>   
    
        function isNotEmpty(value) {
            return value && value.length > 0;
        }
    
        document.addEventListener('livewire:init', function () {
            Livewire.on('servicesUpdated', (event) => {
                let additionalFields = document.getElementById('additional-fields');
                additionalFields.style.display = isNotEmpty(event.hasService) ? 'block' : 'none';
            });
        });
    </script>
    
</x-admin-layout>
