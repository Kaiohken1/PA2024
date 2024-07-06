<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes réservations') }}
        </h2>
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

    </x-slot>

    {{-- @if ($reservations->isEmpty())
        <div class="py-8">
            <h1 class="text-2xl font-semibold mb-4">Récapitulatif de mes réservations</h1>
            <p class="text-gray-600">Vous n'avez aucune réservation pour le moment.</p>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table class="w-full bg-white shadow-md rounded my-4">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Appartement</th>
                            <th class="py-3 px-6 text-left">Prix</th>
                            <th class="py-3 px-6 text-left">Date de début</th>
                            <th class="py-3 px-6 text-left">Date de fin</th>
                            <th class="py-3 px-6 text-left">Date de réservation</th>
                            <th class="py-3 px-6 text-left">Statut</th>
                            <th class="py-3 px-6 text-center">Action</th> 
                        </tr>
                    </thead>
                    @foreach ($reservations as $reservation)
             <tbody class="text-gray-600 text-sm font-light">
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-6 text-left">{{ $reservation->reservation->name }}</td>
                                <td class="py-3 px-6 text-left">{{ $reservation->prix }}€</td>
                                <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y') }}</td>
                                <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y') }}</td>
                                <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($reservation->created_at)->format('d/m/Y H:i:s') }}</td>
                                <td class="py-3 px-6 text-left">{{ $reservation->status }}</td>
                                <td class="py-3 px-6 text-center">
                                    @if (\Carbon\Carbon::now()->subHours(48)->isBefore($reservation->start_time)) 
                                        <form action="{{ route('reservation.cancel', $reservation->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                                Annuler
                                            </button>
                                        </form>
                                    @endif
                                    @if (\Carbon\Carbon::now()->addHours(24)->isAfter($reservation->end_time))
                                        @if (!\App\Models\AppartementAvis::where('user_id', auth()->user()->id)
                                                ->where('reservation_id', $reservation->reservation_id)
                                                ->exists())
                                                <form action="{{ route('avis.create', $reservation->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return">
                                                    Avis
                                                </button>
                                            </form>

                                        @endif 
                                    @endif
                                    <a href="{{route('reservation.generate', $reservation->id)}}">
                                        <button class="btn btn-info">Facture</button>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    @endforeach
                </table>
                {{ $reservations->links() }}
            </div>
        </div>
    @endif --}}


        @foreach($reservations as $reservation)
        <a href="{{route('reservation.show', $reservation->id)}}" class="hover:shadow-md">
            <div class="py-5">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between hover:shadow-md">
                        <div class="p-6 text-gray-900">
                            <article class="flex">
                                <div class="mr-10">
                                    <img class="rounded-md" src="{{ Storage::url($reservation->appartement->images->first()->image) }}" width="200px">
                                </div>
                                <div>
                                    <h1 class="text-2xl font-extrabold">{{ $reservation->appartement->name }}</h1>
                                    <p><span class="font-extrabold">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y') }} - {{$reservation->appartement->city}}</span></p>
                                    <p><span class="font-extrabold text-green-500">{{__('Confirmée')}}</span></p>
                                </div>
                            </article>
                        </div>
                        <div class="flex font-bold text-3xl mr-5 mt-6">{{$reservation->prix}}€</div>
                    </div>
                </div>
            </div>
        </a>
    @endforeach

</x-app-layout>
