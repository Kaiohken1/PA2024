<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fermetures') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h1 class="text-2xl font-semibold mb-4">Choisir une période de fermeture</h1>
                    <div class="overflow-x-auto">
                        <h2 class="text-xl font-semibold mb-4">Ajouter une période de fermeture</h2>
                        <form action="{{ route('fermeture.store', $appartement->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="period_comment" class="block text-sm font-medium text-gray-700">Raison</label>
                                <input type="text" id="period_comment" name="comment" class="form-input mt-1 block w-full"
                                    placeholder="Entrez la raison">
                                <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                            </div>
                            <div class="mb-4">
                                <label for="datePicker" class="block text-sm font-medium text-gray-700">Sélectionnez une période</label>
                                <input type="text" id="datePicker" class="form-input mt-1 block w-full" placeholder="Sélectionnez une période">
                                <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                                <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                            </div>
                            <input type="hidden" id="start_time" name="start_time">
                            <input type="hidden" id="end_time" name="end_time">
                            <div class="mt-4 flex justify-between">
                                <button type="submit" class="btn btn-warning">Enregistrer</button>
                            </div>
                        </form>

                        <h2 class="text-xl font-semibold mb-4">Ajouter un jour de fermeture exceptionnelle</h2>
                        <form action="{{ route('fermeture.store', $appartement->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="single_comment" class="block text-sm font-medium text-gray-700">Raison</label>
                                <input type="text" id="single_comment" name="comment" class="form-input mt-1 block w-full"
                                    placeholder="Entrez la raison">
                                <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                            </div>
                            <div class="mb-4">
                                <label for="datePickerSingle" class="block text-sm font-medium text-gray-700">Sélectionnez une date</label>
                                <input type="text" id="datePickerSingle" class="form-input mt-1 block w-full" placeholder="Sélectionnez une date">
                                <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                                <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                            </div>
                            <input type="hidden" id="singleStart" name="start_time">
                            <input type="hidden" id="singleEnd" name="end_time">
                            <div class="mt-4 flex justify-between">
                                <button type="submit" class="btn btn-warning">Enregistrer</button>
                            </div>
                        </form>

                        <h2 class="text-xl font-semibold mb-4">Ajouter une fermeture récurrente</h2>
                        <form action="{{ route('fermeture.storeRecurring', $appartement->id) }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="mb-4">
                                <label for="recurring_comment" class="block text-sm font-medium text-gray-700">Raison</label>
                                <input type="text" id="recurring_comment" name="comment" class="form-input mt-1 block w-full"
                                    placeholder="Entrez la raison">
                                <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Sélectionnez les jours de la semaine</label>
                                <label class="block text-sm font-medium text-gray-700">Vos fermetures récurentes sont actualisées tous les mois</label>
                                <div class="mt-2 space-y-2">
                                    @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $index => $day)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="days[]" value="{{ $index }}" class="checkbox checkbox-warning"
                                            @if(in_array($index, $appartement->recurringClosures)) checked @endif>
                                            <span class="ml-2">{{ $day }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            </div>
                            <div class="mt-4 flex justify-between">
                                <button type="submit" class="btn btn-warning">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        flatpickr("#datePicker", {
            mode: "range",
            dateFormat: "d-m-Y",
            minDate: "today",
            maxDate: new Date().fp_incr(),
            locale: "fr",
            onChange: function(selectedDates, dateStr, instance) {
                var startDateFormatted = selectedDates[0] ? formatDate(selectedDates[0]) : "";
                var endDateFormatted = selectedDates[1] ? formatDate(selectedDates[1]) : "";
                document.getElementById("start_time").value = startDateFormatted;
                document.getElementById("end_time").value = endDateFormatted;
            },
        });

        flatpickr("#datePickerSingle", {
            mode: "single",
            dateFormat: "d-m-Y",
            minDate: "today",
            maxDate: new Date().fp_incr(),
            locale: "fr",
            onChange: function(selectedDate, dateStr, instance) {
                document.getElementById("singleStart").value = dateStr;
                document.getElementById("singleEnd").value = dateStr;
            }
        });
    });


    function formatDate(date) {
        return date.toLocaleDateString("en-EN");
    }
</script>
