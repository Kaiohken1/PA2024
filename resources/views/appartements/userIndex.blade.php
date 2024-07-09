<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl  leading-tight">
            {{ __('Gestion de mes logements') }}
        </h2>

        <x-nav-link :href="route('reservations.invoices.index')">
            {{ __('Voir mes factures') }}
        </x-nav-link>
        <x-session-statut></x-session-statut>
    </x-slot>

    @foreach($appartements as $appartement)
        @php
        $mainImages = $appartement->images()->where('is_main', true)->orderBy('main_order')->take(4)->get();
        
        $rest = 4 - $mainImages->count();
        
        $otherImages = $appartement->images()->where('is_main', false)->take($rest)->get();
        
        $propertyImages = $mainImages->merge($otherImages);
        @endphp
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-between">
                <div class="p-6 text-gray-900">
                    <article class="flex">
                        <div class="mr-10">
                            @if ($appartement->images->isNotEmpty())
                            <img class="rounded-md" src="{{ Storage::url($propertyImages->first()->image) }}" width="200px">
                            @else
                                Aucune image
                            @endif
                            
                            {{-- <img class="rounded-md" src="{{ Storage::url($appartement->images->first()->image) }}" width="200px"> --}}
                            <h1 class="text-2xl font-extrabold">{{ $appartement->name }}</h1>
                            <p><span class="font-extrabold">{{ $appartement->price }}€</span> {{__('par nuit')}}</p>
                            <p><span class="font-extrabold">{{ $appartement->city }}</span></p>
                            @foreach ($appartement->tags as $tag)
                                <span class="text-blue-900 bg-blue-300 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-100 dark:text-blue-800">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        <div class="stats shadow">
                            <div class="stat">
                              <div class="stat-title">{{__('Total de réservations passées')}}</div>
                              <div class="stat-value">{{$appartement->reservations->count()}}</div>
                              <div class="stat-desc"><a href="{{ route('reservation.showAll', $appartement) }}" class="underline">{{__('Voir toutes les réservations')}}</a></div>
                            </div>
                          </div>
                    </article>
                </div>
                <div class="p-6 grid grid-cols-2 gap-4">
                    <a href="{{ route('property.edit', $appartement) }}">
                        <button class="btn btn-warning w-full">{{__('Editer')}}</button>
                    </a>
                    <a href="{{ route('reservation.showAll', $appartement) }}">
                        <button class="btn btn-warning w-full">{{__('Réservations')}}</button>
                    </a>

                    <a href="{{ route('fermeture.index', $appartement->id) }}" >
                        <button class="btn btn-warning w-full">{{__('Fermetures')}}</button>
                    </a>

                    <a href="{{ route('property-interventions', $appartement->id) }}">
                        <button class="btn btn-warning w-full">{{__('Interventions')}}</button>
                    </a>

                    <a href="{{ route('calendar.show', $appartement->id) }}" class="col-span-2">
                        <button class="btn btn-warning w-full ">{{__('Calendrier')}}</button>
                    </a>

                    @if($appartement->statut_id == 11)
                        <form action="{{ route('property.active-flag', $appartement) }}" method="POST" class="col-span-2">
                            @csrf
                            @method('PATCH')
                            @if($appartement->active_flag == 1)<button class="btn btn-error w-full">{{__('Désactiver')}}</button>@else<button class="btn  btn-success w-full">{{__('Activer')}}</button>@endif
                        </form>
                    @endif

                    @if($appartement->active_flag == 0)
                        <form action="{{ route('property.destroy', $appartement) }}" method="POST" class="col-span-2">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-error w-full" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet appartement ?')">{{__('Supprimer')}}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{$appartements->links()}}
        </div>
    </div>
</x-app-layout>
