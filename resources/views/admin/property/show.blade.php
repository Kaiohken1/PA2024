<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl  leading-tight text-white">
            {{ __('Logement #') }}{{$appartement->id}}
        </h2>

        <x-session-statut></x-session-statut>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">{{ $appartement->name }}</h2>
                    <ul class="list-unstyled">
                        <li><strong>Address:</strong> {{ $appartement->address }}</li>
                        <li><strong>Surface:</strong> {{ $appartement->surface }} m²</li>
                        <li><strong>Nombre de personnes:</strong> {{ $appartement->guestCount }}</li>
                        <li><strong>Salles:</strong> {{ $appartement->roomCount }}</li>
                        <li><strong>Prix:</strong> {{ $appartement->price }}€</li>
                        <li><strong>Type de propriété:</strong> {{ $appartement->property_type }}</li>
                        <li><strong>Ville:</strong> {{ $appartement->city }}</li>
                        <li><strong>Type de location:</strong> {{ $appartement->location_type }}</li>
                        <li><strong>Activé:</strong> {{ $appartement->active_flag ? 'Oui' : 'Non' }}</li>
                    </ul>
                </div>


                <div class="p-6 text-white">
                    <p>Statut : <strong>{{ $appartement->statut->nom }}</strong></p>
                </div>


                <div class="container w-3/4 flex">
                    <div class="carousel w-3/4">
                        @foreach($appartement->images as $index => $image)
                            <div id="slide{{ $index + 1 }}" class="carousel-item relative w-full">
                                <img src="{{ Storage::url($image->image) }}" class="w-full" alt="Image {{ $index + 1 }}" />
                                <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                    <a href="#slide{{ $index == 0 ? count($appartement->images) : $index }}" class="btn btn-circle">❮</a> 
                                    <a href="#slide{{ $index == count($appartement->images) - 1 ? 1 : $index + 2 }}" class="btn btn-circle">❯</a>
                                </div>
                            </div> 
                        @endforeach
                    </div>
                </div>

                @if ($appartement->statut_id == 1)
                <div class="p-6 text-white flex">
                    <form action="{{ route('admin.property.validate', $appartement->id) }}" method="POST" class="mr-5">
                        @csrf
                        @method('patch')
                        <button class="btn btn-warning">
                            Valider le logement
                        </button>
                    </form>

                    <form action="{{ route('admin.property.destroy', $appartement->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-error">
                            Refuser le logement
                        <button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

