<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between hover:shadow-md">
                <div class="p-6 text-gray-900">
                    <p class="font-bold text-2xl mb-3">{{ $reservation->appartement->name }}</p>
                    <div class="stats shadow">
                        <div class="stat">
                            <div class="stat-figure text-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    class="inline-block h-8 w-8 mt-5 stroke-current text-yellow-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="stat-title">{{ __('Arrivée') }}</div>
                            <div class="stat-value text-2xl">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y') }}</div>
                        </div>

                        <div class="stat">
                            <div class="stat-figure text-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    class="inline-block h-8 w-8 mt-5 stroke-current text-yellow-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="stat-title">{{ __('Départ') }}</div>
                            <div class="stat-value text-2xl">
                                {{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div role="alert" class="alert mt-4 bg-white flex">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span class="text-lg"> {{ $reservation->appartement->address }} ·
                            {{ $reservation->appartement->city }}</span>
                        <div>
                            <a class="text-blue-500 hover:underline"
                                href="https://www.google.com/maps/dir/?api=1&destination={{ urlencode($reservation->appartement->address) }}+ {{ urlencode($reservation->appartement->postal_code) }} +{{ urlencode($reservation->appartement->city) }}"
                                target="_blank">{{__('Voir l\'itinéraire')}}</a>
                        </div>
                    </div>

                    <div class="mt-5 mb-5 border rounded-md px-6">
                        <p>{{ __('Tarif HT') }} : {{ number_format($reservation->prix / 1.2, 2) }}€</p>
                        <p class="text-lg">{{ __('Tarif') }} : <span class="font-bold">{{ number_format($reservation->prix, 2) }} €</span></p>
                    </div>

                    <div class="flex">
                        <a href="{{ route('reservation.generate', $reservation->id) }}">
                            <button class="btn btn-info mr-3">{{__('Facture')}}</button>
                        </a>

                        @if (\Carbon\Carbon::now()->addHours(24)->isAfter($reservation->end_time))
                            @if (!\App\Models\AppartementAvis::where('user_id', auth()->user()->id)->where('reservation_id', $reservation->reservation_id)->exists())
                                <form action="{{ route('avis.create', $reservation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success mr-3" onclick="return">
                                        {{__('Laissez un avis')}}
                                    </button>
                                </form>
                            @endif
                        @endif
                        @if (\Carbon\Carbon::now()->subHours(48)->isBefore($reservation->start_time))
                            <form action="{{ route('reservation.cancel', $reservation->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-error"
                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                    {{__('Annuler')}}
                                </button>
                            </form>
                        @endif
                    </div>
                    @if(!\Carbon\Carbon::today()->isAfter($reservation->end_time))
                    <div class="mt-5">
                        <a href="{{ route('interventions.reservation-create', ['id' => $reservation->appartement->id, 'reservationId' => $reservation->id]) }}">
                            <button class="btn btn-warning mr-3">{{__('Réserver un service')}}</button>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
