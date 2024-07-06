<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

<x-provider-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Mes disponibilités</h1>

        <section class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-semibold mb-4">Disponibilité actuelle</h2>
            <div class="flex items-center space-x-4">
                <div>
                    @livewire('availability-toggle')
                </div>
            </div>
        </section>

        <section class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Disponibilités particulières</h2>
            <div>
                <h3 class="text-xl font-semibold mb-2">PÉRIODES D'<span class="text-orange-600">INDISPONIBILITÉ</span></h3>
                <p class="text-gray-600 mb-4">Indiquez les semaines / jours où vous n’êtes pas du tout disponible.</p>
                <div class="mb-4">
                    @forelse ($provider->absences as $absence)
                    <div class="flex">
                        <span class="pr-3">Du {{\Carbon\Carbon::parse($absence->start)->format('d/m/Y')}} au {{\Carbon\Carbon::parse($absence->end)->format('d/m/Y')}}</span>
                        <form action="{{route('provider.availabilityDestroy', $absence->id)}}" method="POST">
                            @csrf
                            @method('delete')

                            <button class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current text-gray-600 transition-colors duration-300 hover:text-red-600" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 5a1 1 0 011-1h10a1 1 0 011 1v1h2a1 1 0 011 1v1a2 2 0 01-2 2H4a2 2 0 01-2-2V7a1 1 0 011-1h2V5zm0 2v9a1 1 0 001 1h10a1 1 0 001-1V7H4z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                        </form>
                    </div>
                    @empty
                        Vous n'avez aucune période d'indisponibilité
                    @endforelse
                </div>
                <button class="btn btn-warning" onclick="document.getElementById('add-period-modal').classList.toggle('hidden')">Ajouter une période</button>
                <button class="btn btn-warning" onclick="document.getElementById('add-single-day-modal').classList.toggle('hidden')">Ajouter une journée</button>

            </div>
        </section>
    </div>

    <div id="add-period-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Ajouter une période d'indisponibilité</h2>
            <input type="text" id="datePicker" placeholder="Sélectionnez une date">
            <div class="mt-4 flex justify-between">
                <button class="btn btn mr-2" onclick="document.getElementById('add-period-modal').classList.toggle('hidden')">Annuler</button>
                <form action={{route('provider.availabilityCreate')}} method="POST">
                    @csrf
                    <input type="hidden" id="startDate" name="startDate">
                    <input type="hidden" id="endDate" name="endDate">
                    <input type="hidden" id="providerId" name="providerId" value="{{Auth::user()->provider->id}}">
                    <button class="btn btn-warning">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <div id="add-single-day-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Ajouter un jour d'indisponibilité</h2>
            <input type="text" id="datePickerSingle" placeholder="Sélectionnez une date">
            <div class="mt-4 flex justify-between">
                <button class="btn btn mr-2" onclick="document.getElementById('add-single-day-modal').classList.toggle('hidden')">Annuler</button>
                <form action={{ route('provider.availabilityCreate') }} method="POST">
                    @csrf
                    <input type="hidden" id="singleStart" name="startDate">
                    <input type="hidden" id="singleEnd" name="endDate">
                    <input type="hidden" id="providerId" name="providerId" value="{{Auth::user()->provider->id}}">
                    <button class="btn btn-warning">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</x-provider-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var disabledDates = <?php echo json_encode($datesInBase); ?>;
        
        flatpickr("#datePicker", {
            mode: "range", 
            dateFormat: "d-m-Y", 
            minDate: "today", 
            maxDate: new Date().fp_incr(), 
            locale: "fr",
            disable: disabledDates,
            onChange: function(selectedDates, dateStr, instance) {
                var startDateFormatted = selectedDates[0] ? formatDate(selectedDates[0]) : "";
                var endDateFormatted = selectedDates[1] ? formatDate(selectedDates[1]) : "";
                document.getElementById("startDate").value = startDateFormatted;
                document.getElementById("endDate").value = endDateFormatted;
            },
        });

        flatpickr("#datePickerSingle", {
            mode: "single",
            dateFormat: "d-m-Y",
            minDate: "today",
            maxDate: new Date().fp_incr(),
            locale: "fr",
            disable: disabledDates,
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

