<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">{{ $intervention->user->name }} {{ $intervention->user->first_name }}</h2>
                    <p><strong>Service:</strong> {{ $intervention->services->getModel()->name }}</p>
                    <p><strong>Prix:</strong> {{ $intervention->services->getModel()->price }}€</p>
                    @foreach ($intervention->service_parameters as $parameter)
                        <div class="flex">
                            @php
                            $version = Mpociot\Versionable\Version::find($parameter->pivot->parameter_version);
                            @endphp
                            <p class="mr-3"><strong>{{ $version->getModel()->name}} :</strong> </p>
                            <p><strong>{{ $parameter->pivot->value }} </strong>
                        </div>
                    @endforeach

                    <p><strong>Description:</strong> {{ $intervention->description }}</p>
                    <p><strong>Créé le:</strong> {{ \Carbon\Carbon::parse($intervention->created_at)->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Edité le:</strong> {{ \Carbon\Carbon::parse($intervention->updated_at)->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
