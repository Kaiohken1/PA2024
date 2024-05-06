<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
                <form action="{{ route('fermeture.store', $appartement) }}" method="POST">
                    @csrf

                    <x-input-label for="start_time" :value="__('Date de début')" />
                    <x-text-input id="start_time" class="block mt-1 w-full" type="date" name="start_time"/>
                    <x-input-error :messages="$errors->get('start_time')" class="mt-2" />

                    <x-input-label for="end_time" :value="__('Date de fin')" />
                    <x-text-input id="end_time" class="block mt-1 w-full" type="date" name="end_time"/>
                    <x-input-error :messages="$errors->get('end_time')" class="mt-2" />

                    <x-primary-button class="ms-3 mt-5 ml-0">
                    {{ __('Ajouter une période de fermeture') }}
                    </x-primary-button>
                </form>
            </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function estDansIntervalle(date, intervalles, fermetures) {

        var currentDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());

        for (var i = 0; i < intervalles.length; i++) {
            var startDate = new Date(intervalles[i].start_time);
            var endDate = new Date(intervalles[i].end_time);
            var intervalleStartDate = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate());
            var intervalleEndDate = new Date(endDate.getFullYear(), endDate.getMonth(), endDate.getDate());
            if (currentDate >= intervalleStartDate && currentDate <= intervalleEndDate) {
                return true;
            }
        }
        for (var i = 0; i < fermetures.length; i++) {
            var fermetureStart = new Date(fermetures[i].start_time);
            var fermetureEnd = new Date(fermetures[i].end_time);
            var trueFermetureStart = new Date(fermetureStart.getFullYear(), fermetureStart.getMonth(), fermetureStart.getDate());
            var trueFermetureEnd = new Date(fermetureEnd.getFullYear(), fermetureEnd.getMonth(), fermetureEnd.getDate());
            if (currentDate >= trueFermetureStart && currentDate <= trueFermetureEnd) {
                return true;
            }
        }
        return false;
    }

    var intervallesADesactiver = @json($intervalles);
    var fermeturesADesactiver = @json($fermetures);

    console.log(intervallesADesactiver);
    console.log(fermeturesADesactiver);

    var demain = new Date();
    demain.setDate(demain.getDate() + 1);

    flatpickr('#start_time', {
        dateFormat: 'Y-m-d',
        minDate: demain,
        disable:[
            function(date) {
                return estDansIntervalle(date, intervallesADesactiver, fermeturesADesactiver);
            }
            ]
    });

    flatpickr('#end_time', {
        dateFormat: 'Y-m-d',
        minDate: demain,
        disable:[
            function(date) {
                return estDansIntervalle(date, intervallesADesactiver, fermeturesADesactiver);
            }
        ]
    });
</script>
