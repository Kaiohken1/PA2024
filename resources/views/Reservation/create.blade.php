<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservation!') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <h1 class="text-2xl font-semibold mb-4">Formulaire de réservation</h1>

        <form method="POST" action="{{ route('reservation.store') }}">
            @csrf

            <input type="hidden" name="appartement_id" value="{{ $selectedAppartement->id }}">

            <div class="mb-4">
                <label for="start_time" class="block text-gray-700 text-sm font-bold mb-2">Date et heure de début :</label>
                <input type="date" name="start_time" id="start_time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('start_time')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">Date et heure de fin :</label>
                <input type="date" name="end_time" id="end_time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('end_time')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nombre_de_personne" class="block text-gray-700 text-sm font-bold mb-2">Nombre de personne :</label>
                <input type="number" name="nombre_de_personne" id="nombre_de_personne" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('nombre_de_personne')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            
            <p>Total : <span id="total_price">0 €</span></p>
            <input type="hidden" name="prix" id="prix">

            <div class="mb-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Réserver</button>
            </div>
        </form>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('start_time').addEventListener('change', updateTotalPrice);
            document.getElementById('end_time').addEventListener('change', updateTotalPrice);
            document.getElementById('nombre_de_personne').addEventListener('input', updateTotalPrice);



            function updateTotalPrice() {

                var startTime = new Date(document.getElementById('start_time').value);
                var endTime = new Date(document.getElementById('end_time').value);
                var numberOfPersons = parseInt(document.getElementById('nombre_de_personne').value);
                var pricePerNight = parseFloat("{{ $selectedAppartement->price }}"); 

                var pricePerNight = parseFloat("{{ $selectedAppartement->price }}");


                if (!isNaN(startTime) && !isNaN(endTime) && startTime < endTime && numberOfPersons > 0 && !isNaN(pricePerNight)) {

                    var numberOfNights = Math.ceil((endTime - startTime) / (1000 * 3600 * 24));


                    var totalPrice = numberOfNights * pricePerNight * numberOfPersons;


                    document.getElementById('total_price').innerText = totalPrice.toFixed(2) + ' €';


                    document.getElementById('prix').value = totalPrice;
                } else {

                    document.getElementById('total_price').innerText = '0€';
                    document.getElementById('prix').value = '';
                }
            }
        });
        var reservedDates = @json($selectedAppartement->reservations->pluck('start_time', 'end_time')->flatten()->map(function($date) {
            return $date->format('Y-m-d');
        })->toArray());

        $('#start_time, #end_time').datepicker({
            dateFormat: 'yy-mm-dd',
            beforeShowDay: function(date) {
                var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                return [!reservedDates.includes(dateString)];
            }
        });

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

        var demain = new Date();
        demain.setDate(demain.getDate() + 1);

        flatpickr('#start_time', {
            dateFormat: 'Y-m-d', // Format de la date
            minDate: demain, // Limiter la sélection aux dates postérieures à aujourd'hui
            disable:[
                function(date) {

                // Désactiver les dates dans les intervalles spécifiés
                return estDansIntervalle(date, intervallesADesactiver, fermeturesADesactiver);
                }
            ]
        });

        flatpickr('#end_time', {
            dateFormat: 'Y-m-d', // Format de la date
            minDate: demain, // Limiter la sélection aux dates postérieures à aujourd'hui
            disable:[
                function(date) {

                    // Désactiver les dates dans les intervalles spécifiés
                    return estDansIntervalle(date, intervallesADesactiver, fermeturesADesactiver);
                }
            ]
        });
    </script>
    
 
    


</x-app-layout>
