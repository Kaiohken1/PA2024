<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">Demande d'intervention {{$intervention->id}}</h2>
                    <p><strong>Client :</strong> {{ $intervention->user->name }} {{ $intervention->user->first_name }}</p>
                    <p><strong>Service:</strong> {{ $intervention->services->getModel()->name }}</p>
                    <p><strong>Prestataire en charge:</strong> @if($intervention->provider){{ $intervention->provider->name }}@else À définir @endif</p>
                    <p><strong>Prix:</strong> @if($intervention->price){{ $intervention->price }}€@else À définir par le prestataire @endif</p>
                    <p><strong>Commission :</strong> @if($intervention->price){{ $intervention->commission }}€@else À définir @endif</p>
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 text-white">
                    <h2 class="text-lg font-bold mt-5 border-b">Historique des refus</h2>
                    @foreach($intervention->refusals as $refusals)
                        @if($refusals->statut_id == 8)
                            <div class="border-b border-gray-200 py-2">
                                <p class="text-lg"><label class="pr-10">Refusé le</label> {{ $refusals->created_at->format('d/m/Y à H:i:s') }}</p>
                                <p class="text-lg"><label class="pr-10">Raison</label> {{ $refusals->refusal_reason }}</p>
                                <p class="text-lg"><label class="pr-10">Prestataire</label> {{ $refusals->provider->name}}</p>
                                <p class="text-lg"><label class="pr-10">Montant</label> {{ $refusals->price ? $refusals->price . '€' : 'À définir' }}</p>
                                <p class="text-lg"><label class="pr-10">Date et heure de fin prévue</label> {{ $refusals->end_time ? \Carbon\Carbon::parse($refusals->end_time)->format('d/m/Y à H:i:s') : 'À définir' }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
