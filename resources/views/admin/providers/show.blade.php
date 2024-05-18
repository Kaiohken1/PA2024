<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">{{ $provider->name }}</h2>
                    <img src="{{ Storage::url($provider->avatar) }}" width="200" alt="Avatar du fournisseur">
                    <p><strong>Email :</strong> {{ $provider->email }}</p>
                    <p><strong>Adresse :</strong> {{ $provider->address }}</p>
                    <p><strong>Numéro de téléphone :</strong> {{ $provider->phone }}</p>
                    <p><strong>Description :</strong> {{ $provider->description }}</p>
                    <p><strong>Utilisateur :</strong> {{ $provider->user->name }} {{ $provider->user->first_name }}</p>
                </div>

                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">Service proposé : </h2>
                        <p><strong>{{ $service->name }}</strong></p>
                        <p><strong>Description:</strong> {{ $service->pivot->description }}</p>
                    @if($service->pivot->price_scale)<a href="{{ Storage::url($service->pivot->price_scale) }}" class="mt-2"><strong><u>Barème</u></strong></a>@endif
                </div>

                <div class="p-6 text-white">
                    <h3 class="text-2xl font-bold mb-4">Documents envoyés : </h3>
                    @foreach ($provider->documents as $document)
                        <p>{{$document->name}} : 
                            <a href="{{Storage::url($document->pivot->document)}}"><strong><u>Télécharger</strong></u></a>
                        </p>
                    @endforeach
                </div>

                <div class="p-6 text-white">
                    <p>Statut : <strong>{{ $provider->statut }}</strong></p>
                </div>

                @if ($provider->statut === 'en attente' && Auth::user()->isAdmin())
                <div class="p-6 text-white flex">
                    <form action="{{ route('admin.providers.validate', $provider) }}" method="POST" class="mr-5">
                        @csrf
                        @method('patch')
                        <x-primary-button>
                            Valider le prestataire
                        </x-primary-button>
                    </form>

                    <form action="{{ route('admin.providers.destroy', $provider) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>
                            Refuser le prestataire
                        </x-danger-button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
