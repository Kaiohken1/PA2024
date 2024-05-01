<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">{{ $provider->name }}</h2>
                    <img src="{{ Storage::url($provider->avatar) }}" width="200" alt="Avatar du fournisseur">
                    <p><strong>Email:</strong> {{ $provider->email }}</p>
                    <p><strong>Adresse:</strong> {{ $provider->address }}</p>
                    <p><strong>Description:</strong> {{ $provider->description }}</p>
                    <p><strong>Utilisateur :</strong> {{ $provider->user->name }} {{ $provider->user->first_name }}</p>
                </div>

                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">Services proposés : </h2>
                    @foreach ($services as $service)
                        <p><strong>Service:</strong> {{ $service->name }}</p>
                        <p><strong>Prix:</strong> {{ $service->pivot->price }}€</p>
                        <p><strong>Prix évolutif:</strong> {{ $service->pivot->flexPrice ? 'Oui' : 'Non' }}</p>
                        <p><strong>Description:</strong> {{ $service->pivot->description }}</p>
                        <a href="{{ Storage::url($service->pivot->habilitationImg) }}"><strong><u>Habilitation(PDF)</u></strong></a>
                    @endforeach
                </div>

                <div class="p-6 text-white">
                    <p>Statut : <strong>{{ $provider->statut }}</strong></p>
                </div>

                @if ($provider->statut === 'en attente' && Auth::user()->isAdmin())
                <form action=""></form>
                <div class="p-6 text-white">
                    <form action="{{ route('providers.validate', $provider) }}" method="POST">
                        @csrf
                        @method('patch')
                        <x-primary-button>
                            Valider le prestataire
                        </x-primary-button>
                    </form>

                    <form action="{{ route('providers.destroy', $provider) }}" method="POST">
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
