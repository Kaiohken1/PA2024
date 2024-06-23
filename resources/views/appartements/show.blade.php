<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>


<x-app-layout>
    @if (session('success'))
    <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600" role="alert">
        {{ session('success') }}
    </div>
    @elseif (session('error'))
    <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600" role="alert">
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
                <button class="btn btn-info"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'images-show')"
                >{{ __('Voir toutes les photos') }}</button>
        
                <x-modal name="images-show" focusable  maxWidth="fit" maxHeight="full">
                    <div class="p-4 w-full">
                        <div class="flex justify-end mb-4">
                            <button @click="$dispatch('close')" class="text-black hover:bg-gray-300 rounded-full p-2 transition duration-300 ease-in-out">
                                <p class="text-xl"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                  </svg>
                                  </p>
                            </button>                        
                        </div>
                        <div class="flex">
                            @foreach ($appartement->images as $image)
                                <div class="w-full">
                                    <a
                                    x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', 'images-details')"
                                    >
                                    <img class="h-72 max-w-full rounded-lg object-cover" src="{{ Storage::url($image->image) }}" alt="Image de l'appartement">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <x-modal name="images-details" maxWidth="full" maxHeight="full">
                            <div class="container w-full">
                                @foreach($appartement->images as $index => $image)
                                    <div id="slide{{ $index + 1 }}" class="carousel-item relative w-half h-half">
                                        <img src="{{ Storage::url($image->image) }}" class="w-full h-2/4 object-contain" alt="Image {{ $index + 1 }}" />                                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                            <a href="#slide{{ $index == 0 ? count($appartement->images) : $index }}" class="btn btn-circle">❮</a> 
                                            <a href="#slide{{ $index == count($appartement->images) - 1 ? 1 : $index + 2 }}" class="btn btn-circle">❯</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </x-modal>
                    </div>
                </x-modal>
                <div class="flex justify-between mt-5">
                    <div class="mt-1 w-80">
                        @foreach ($appartement->tags as $tag)
                        <span class="bg-blue-900 text-blue-300 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-100 dark:text-blue-800">{{$tag->name}}</span>
                        @endforeach
                        <p class="text-xl"><span>{{ $appartement->guestCount }} voyageurs</span> · <span>{{ $appartement->roomCount }} chambres</span></p>
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
                                <label for="start_time" class="block text-gray-700 text-sm font-bold mb-2">Date de début :</label>
                                <input type="date" name="start_time" id="start_time"
                                    min="{{ \Carbon\Carbon::now()->toDateString() }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Arrivée" readonly>
                                @error('start_time')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">Date de fin :</label>
                                <input type="text" name="end_time" id="end_time"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Départ" readonly>
                            </div>

                            <div class="mb-4">
                                <label for="nombre_de_personne" class="block text-gray-700 text-sm font-bold mb-2">Nombre de personnes :</label>
                                <input type="number" name="nombre_de_personne" id="nombre_de_personne"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    min="1" max="{{ $appartement->guestCount }}">
                                @error('nombre_de_personne')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4" id="total_price_container">
                                <p>Total : <span id="total_price">{{$appartement->price}}€</span></p>
                                <input type="hidden" name="prix" id="prix" value="{{$appartement->price}}">
                            </div>

                            <div class="mb-4">
                                <x-primary-button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Réserver
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                    
                    
            </article>
            <div>@include('appartements.appartement_avis.indexAvis')</div>
        </div>
    </div>

</x-app-layout>

<script>
    var disabledDates = <?php echo json_encode($datesInBase); ?>;

    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('start_time').addEventListener('change', updateTotalPrice);
        document.getElementById('end_time').addEventListener('change', updateTotalPrice);
        document.getElementById('nombre_de_personne').addEventListener('input', updateTotalPrice);

        document.getElementById("start_time").addEventListener('focus', function(event) {
            event.target.showPicker();
        });

        document.getElementById("end_time").addEventListener('focus', function(event) {
            event.target.showPicker();
        });

        document.getElementById('start_time').addEventListener('change', disableReservedDates);
        document.getElementById('end_time').addEventListener('change', disableReservedDates);

        function disableReservedDates() {
            var start = new Date(document.getElementById('start_time').value);
            var end = new Date(document.getElementById('end_time').value);

            if (end < start) {
                alert("La date de fin ne peut pas être antérieure à la date de début.");
                document.getElementById('end_time').value = '';
                return;
            }
        }
    });

    flatpickr("#start_time", {
    mode: "range",
    dateFormat: "d-m-Y",
    minDate: "today",
    showMonths: 2,
    locale: "fr",
    disable: disabledDates,
    onClose: function(selectedDates) {
        if (selectedDates.length > 0) {
            var start = selectedDates[0];
            var end = selectedDates[selectedDates.length - 1];
            document.getElementById("start_time").value = formatDate(start);
            document.getElementById("end_time").value = formatDate(end, true);
        }
    }
});

function formatDate(date, endOfDay = false) {
    if (endOfDay) {
        date.setHours(23, 59, 59, 999);
    }
    var year = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, "0");
    var day = date.getDate().toString().padStart(2, "0");
    return day + "-" + month + "-" + year;
}

</script>
