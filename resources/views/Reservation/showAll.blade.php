<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Réservation de l\'appartement : ' . $appartement_name) }}
        </h2>

        @if (session('success'))
        <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-yellow-50  dark:text-green-600"
            role="alert">
            {{ session('success') }}
        </div>
    @endif

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="w-full bg-white shadow-md rounded my-4">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Appartement</th>
                        <th class="py-3 px-6 text-left">Locataire</th>
                        <th class="py-3 px-6 text-left">Prix</th>
                        <th class="py-3 px-6 text-left">Date de début</th>
                        <th class="py-3 px-6 text-left">Date de fin</th>
                        <th class="py-3 px-6 text-left">Date de réservation</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-center">Action</th>
                    </tr>
                </thead>
                @foreach ($reservations as $reservation)
                    <tbody class="text-gray-600 text-sm font-light">
                        <tr class="border-b border-gray-200">
                            <td class="py-3 px-6 text-left">{{ $reservation->appartement->name }}</td>
                            <td class="py-3 px-6 text-left">{{ $reservation->user->name }}</td>
                            <td class="py-3 px-6 text-left">{{ $reservation->prix }}€</td>
                            <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y') }}</td>
                            <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y') }}</td>
                            <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($reservation->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="py-3 px-6 text-left">{{ $reservation->status }}</td>
                            @if ($reservation->status === 'en attente')
                                <td class="py-3 px-6 text-center">
                                    <form method="POST" action="{{ route('reservation.validate', $reservation) }}"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-500 hover:text-green-700 ml-2">
                                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                            <span class="ml-1 mr-3">Valider</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('reservation.refused', $reservation) }}"
                                        class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-500 hover:text-red-700"
                                            onclick="return confirm('Êtes-vous sûr de vouloir refuser cette réservation ?')">
                                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span class="ml-1">Refuser</span>
                                        </button>
                                    </form>
                                </td>
                            @else
                                <td class="py-3 px-6 text-center"></td>
                            @endif
                        </tr>
                    </tbody>
                @endforeach
            </table>
            {{ $reservations->links() }}

        </div>
    </div>
</x-app-layout>
