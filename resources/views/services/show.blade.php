<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">{{ $service->name }}</h2>
                    <p><strong>Prix:</strong> {{ $service->price }}€</p>
                    <p><strong>Description:</strong> {{ $service->description }}</p>
                    <p><strong>Créé le:</strong> {{ \Carbon\Carbon::parse($service->created_at)->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Edité le:</strong> {{ \Carbon\Carbon::parse($service->updated_at)->format('d/m/Y H:i:s') }}</p>


                    <h2 class="text-2xl font-bold mt-4 mb-2">Paramètres</h2>
                    @foreach ($service->parameters as $parameter)
                        <div class="flex">
                            <p class="mr-3"><strong>Nom : </strong> {{ $parameter->name }}</p>
                            <p><strong>Type de donnée : </strong>{{ $parameter->dataType->name }}</p>
                        </div>
                    @endforeach

                    <h2 class="text-2xl font-bold mt-4 mb-2">Documents requis</h2>
                    @foreach ($service->documents as $document)
                        <div class="flex">
                            <p class="mr-3"><strong>Nom : </strong> {{ $document->name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
