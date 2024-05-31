<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">Demande d'intervention {{$intervention->id}}</h2>
                    <p><strong>Client :</strong> {{ $intervention->user->name }} {{ $intervention->user->first_name }}</p>
                    <p><strong>Service:</strong> {{ $intervention->services->getModel()->name }}</p>
                    <p><strong>Prestataire en charge:</strong> @if($intervention->provider){{ $intervention->provider->name }}@else À définir @endif</p>
                    <p><strong>Prix:</strong> @if($intervention->services->getModel()->price){{ $intervention->services->getModel()->price }}€@else À définir par le prestataire @endif</p>
                    <p><strong>Date d'intervention:</strong> {{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y H:i:s')}}</p>
                    <p><strong>Statut:</strong> {{ $intervention->statut->nom }}</p>
                    @foreach ($intervention->service_parameters as $parameter)
                        <div class="flex">
                            @php
                            $version = Mpociot\Versionable\Version::find($parameter->pivot->parameter_version);
                            @endphp
                            <p class="mr-3"><strong>{{ $version->getModel()->name}} :</strong> </p>
                            <p><strong>{{ $parameter->pivot->value }} </strong>
                        </div>
                    @endforeach

                    @if($intervention->description)<p><strong>Description:</strong> {{ $intervention->description }}</p>@endif
                    <p><strong>Créé le:</strong> {{ \Carbon\Carbon::parse($intervention->created_at)->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Edité le:</strong> {{ \Carbon\Carbon::parse($intervention->updated_at)->format('d/m/Y H:i:s') }}</p>


                    <form action="{{ route('providers.available', $intervention->id) }}" method="GET">
                        @csrf

                        <input type="hidden" value="{{$intervention->id}}" name="intervention_id">
                        <input type="hidden" value="{{$intervention->planned_date}}" name="start">
                        <input type="hidden" value="{{$intervention->service_id}}" name="service_id">
                    
                        <button type="submit">Rechercher</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
