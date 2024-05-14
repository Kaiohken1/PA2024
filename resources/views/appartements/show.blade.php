<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<x-app-layout>
    @if (session('success'))
    <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600"
        role="alert">
        {{ session('success') }}
    </div>
    @elseif (session('error'))
    <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600"
        role="alert">
        {{ session('error') }}
    </div>
    @endif
    <div class="flex justify-center">
        <div class="mt-9 ml-11">
            <article>
                <h1 class="text-3xl font-extrabold">{{ $appartement->name }}</h1>
                @if (count($appartement->images) == 1)
                <img class="rounded-md" src="{{ Storage::url($appartement->images->first()->image) }}" width="100%">
                @else
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($appartement->images as $image)
                    <div class="w-full">
                        <img class="h-72 max-w-full rounded-lg" src="{{ Storage::url($image->image) }}" width="100%">
                    </div>
                    @endforeach
                </div>
                @endif
                <div class="flex justify-between mt-5">
                    <div class="mt-1 w-80">
                        @foreach ($appartement->tags as $tag)
                        <span class="bg-blue-900 text-blue-300 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-100 dark:text-blue-800">{{$tag->name}}</span>
                        @endforeach
                        <p class="text-xl"><span>{{ $appartement->guestCount }} voyageurs</span> ·
                            <span>{{ $appartement->roomCount }} chambres</span>
                        </p>
                        <p class="text-xl">{{ $appartement->address }}</p>
                        <p class="text-xl">Loué par {{ $appartement->user->name }}</p>

                        <p class="mt-10">Description</p>
                        <div class="border-t-2 border-grey overflow-x-auto">
                            <p class="text-xl">{{ $appartement->description }}</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-8 ml-20 bg-white sm:rounded-lg shadow-xl">
                        <p class="text-xl"><span class="font-extrabold">{{ $appartement->price }}€</span> par nuit</p>

                        <form method="POST" action="{{ route('reservation.store') }}">
                            @csrf

                            <input type="hidden" name="appartement_id" value="{{ $appartement->id }}">

                            <div class="mb-4">
                                <label for="nombre_de_personne" class="block text-gray-700 text-sm font-bold mb-2">Nombre de personnes :</label>
                                <input type="number" name="nombre_de_personne" id="nombre_de_personne"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    min="1" max="{{ $appartement->guestCount }}">
                                @error('nombre_de_personne')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Date de début :</label>
                                <input type="text" name="start_date" id="start_date"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Sélectionnez la date de début" readonly>
                            </div>
                            
                            <div class="mb-4">
                                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Date de fin :</label>
                                <input type="text" name="end_date" id="end_date"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Sélectionnez la date de fin" readonly>
                            </div>
                            

                            <div class="mb-4" id="total_price_container" style="display: none;">
                                <p>Total : <span id="total_price">0 €</span></p>
                                <input type="hidden" name="prix" id="prix">
                            </div>

                            <div class="mb-4">
                                <x-primary-button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Réserver
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </article>
            </div>
        </div>
    </x-app-layout>
</script>

<script>
    var intervallesADesactiver = @json($intervalles);
    var fermeturesADesactiver = @json($fermetures);

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

    flatpickr('#start_date', {
    dateFormat: 'd-m-Y',
    minDate: demain,
    onChange: function(selectedDates, dateStr, instance) {
        var endDatePicker = flatpickr('#end_date');
        endDatePicker.set('minDate', selectedDates[0]);
    }
});

flatpickr('#end_date', {
    dateFormat: 'd-m-Y',
    minDate: demain,
    onChange: function(selectedDates, dateStr, instance) {
        var startDatePicker = flatpickr('#start_date');
        startDatePicker.set('maxDate', selectedDates[0]);
    }
});
</script>
