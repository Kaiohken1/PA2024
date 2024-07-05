<div>
    <div>
        <a href="{{route('providers.proposals')}}" class="flex">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hover:underline">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
          </svg>
        <p class="hover:underline">Retourner au tableau des propostions</p>
        </a>
    </div>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Paramètres des villes</h1>

        <section class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-semibold mb-4">Gérer les villes pour lequelles vous souhaitez voir les propositions d'intervention</h2>
            <div class="flex items-center space-x-4">
                <div>
                    @foreach($cities as $city)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model.live="selectedCities" value="{{ $city }}" class="checkbox checkbox-warning">
                        <span class="text-gray-700">{{ $city }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </section>

</div>
