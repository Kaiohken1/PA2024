<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-4">{{ $provider->name }}</h2>
                    <img src="{{ Storage::url($provider->avatar) }}" width="200" alt="Avatar du fournisseur">
                    <p><strong>Email:</strong> {{ $provider->email }}</p>
                    <p><strong>Adresse:</strong> {{ $provider->address }}</p>
                    <p><strong>Description:</strong> {{ $provider->description }}</p>
                    <p><strong>Utilisateur :</strong> {{ $provider->user->name }} {{ $provider->user->first_name }}</p>
                </div>

                <div class="p-6 text-gray-900">
                    <h2 class="text-2xl font-bold mb-4">Services proposés : </h2>
                    @foreach ($services as $service)
                        <p><strong>Service:</strong> {{ $service->name }}</p>
                        <p><strong>Prix:</strong> {{ $service->pivot->price }}€</p>
                        <p><strong>Prix évolutif:</strong> {{ $service->pivot->flexPrice ? 'Oui' : 'Non' }}</p>
                        <p><strong>Description:</strong> {{ $service->pivot->description }}</p>
                        <a href="{{ Storage::url($service->pivot->habilitationImg) }}"><strong><u>Habilitation</u></strong></a>
                    @endforeach
                </div>

                <div class="p-6 text-gray-900">
                    <p>Statut : <strong>{{ $provider->statut }}</strong></p>
                </div>

                @if ($provider->statut === 'en attente' && Auth::user()->isAdmin())
                <div class="p-6 text-gray-900">
                    <x-primary-button>
                        Valider le prestataire
                    </x-primary-button>
                    <x-secondary-button>
                        Refuser le prestataire
                    </x-secondary-button>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
