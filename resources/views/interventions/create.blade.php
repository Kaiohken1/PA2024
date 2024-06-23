<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

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

                <div id="additional-fields" style="display: none;">
                    <div>
                        <x-input-label for="planned_date" :value="__('Date prévue')"/>
                        <input type="text" id="planned_date" name="planned_date" placeholder="Sélectionnez une date">
                        <x-input-error class="mt-2" :messages="$errors->get('planned_date')" />
                    </div>

                    <div>
                        <x-input-label for="max_end_date" :value="__('Date de fin limite')"/>
                        <input type="text" id="max_end_date" name="max_end_date" placeholder="Sélectionnez une date" disabled>
                        <x-input-error class="mt-2" :messages="$errors->get('max_end_date')" />
                    </div>

                    <div>
                        <p>{{ __('Autre information dont vous souhaiteriez nous faire part (facultatif)') }}</p>
                        <textarea name="information" class="block mt-1 w-full"></textarea>
                    </div>
                    <x-primary-button class="mt-4">{{ __('Demander une prestation') }}</x-primary-button>
                </div>

            </form>
        @else
            <p>Aucun service n'est disponible actuellement</p>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const plannedDatePicker = flatpickr("#planned_date", {
                mode: "single",
                enableTime: true,
                dateFormat: "d-m-Y H:i",
                minDate: "today",
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
                minDate: "today",
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
