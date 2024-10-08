<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
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
                            @if($reservation !== NULL)<input type="hidden" name="reservation_id" value="{{ $reservation->id }}">@endif


                            <livewire:service-form :id="$selectedAppartement->id"/>

                            <div id="additional-fields" style="display: none;">
                                <div>
                                    <x-input-label for="planned_date" :value="__('Date prévue')"/>
                                    <input type="text" id="planned_date" name="planned_date" placeholder="Sélectionnez une date" class="input input-bordered input-warning w-full max-w-xs">
                                    <x-input-error class="mt-2" :messages="$errors->get('planned_date')" />
                                </div>

                                <div>
                                    <x-input-label for="max_end_date" :value="__('Date de fin limite (facultatif)')"/>
                                    <input type="text" id="max_end_date" name="max_end_date" placeholder="Sélectionnez une date"  class="input input-bordered input-warning w-full max-w-xs" disabled>
                                    <x-input-error class="mt-2" :messages="$errors->get('max_end_date')" />
                                </div>

                                <div>
                                    <p>{{ __('Autre information dont vous souhaiteriez nous faire part ') }}</p>
                                    <x-input-label for="planned_date" :value="__('Essayez d\'exprimer au mieux votre besoin afin que nous puissons vous fournir une prestation adaptée')"/>
                                    <textarea name="information"  class="textarea textarea-warning w-full" ></textarea>
                                </div>
                                <button class="mt-4 btn btn-warning">{{ __('Demander une prestation') }}</button>
                            </div>

                        </form>
                    @else
                        <p>Aucun service n'est disponible actuellement</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const start_time = @json($start_time);
            const end_time = @json($end_time);

            const today = new Date();
            const minDate = new Date(today);
            minDate.setDate(today.getDate() + 2);
    
            const plannedDatePicker = flatpickr("#planned_date", {
                mode: "single",
                enableTime: true,
                dateFormat: "d-m-Y H:i",
                minDate: start_time ? new Date(start_time) : minDate,
                maxDate: end_time ? new Date(end_time) : null,
                locale: "fr",
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        document.getElementById('max_end_date').disabled = false;
                        maxEndDatePicker.set('minDate', selectedDates[0]);
                    } else {
                        document.getElementById('max_end_date').disabled = false;
                    }
                }
            });
    
            const maxEndDatePicker = flatpickr("#max_end_date", {
                mode: "single",
                enableTime: true,
                dateFormat: "d-m-Y H:i",
                minDate: start_time ? new Date(start_time) : "today",
                maxDate: end_time ? new Date(end_time) : null,
                locale: "fr",
            });
        });
    
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
    
</x-app-layout>
