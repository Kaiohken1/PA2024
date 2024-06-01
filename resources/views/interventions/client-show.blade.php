<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div>
                    <h1 class="text-2xl font-bold flex justify-center">Intervention #{{$intervention->id}}</h1>

                    <h2 class="text-lg font-bold mt-5 border-b">Détails de l'intervention</h2>

                    <p class="text-lg"><label class="text-yellow-800 pr-10">Service</label> {{$intervention->service->name}} </p>

                    <p class="text-lg"><label class="text-yellow-800 pr-10">Prestataire</label> @if($intervention->provider) {{$intervention->provider->name}} @else À définir @endif</p>

                    @foreach($intervention->service_parameters as $parameter)
                        @php
                        $version = Mpociot\Versionable\Version::find($parameter->pivot->parameter_version);
                        @endphp
                        <p class="text-lg"><label class="text-yellow-800 pr-10">{{$version->getModel()->name}}</label> {{$parameter->pivot->value}} </p>
                    @endforeach

                    <p class="text-lg"><label class="text-yellow-800 pr-10">Date et heure de début</label> Le {{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y à H:i:s')}} </p>

                    <p class="text-lg"><label class="text-yellow-800 pr-10">Date et heure de fin</label> Le {{\Carbon\Carbon::parse($intervention->planned_end_date)->format('d/m/Y à H:i:s')}} </p>

                    <p class="text-lg"><label class="text-yellow-800 pr-10">Montant à payer</label> @if($intervention->price) <strong>{{$intervention->price}}€</strong> @else À définir @endif</p>
                </div>

                <div class="mt-4 flex flex-col">
                    @if($intervention->provider) 
                    <a href="{{ Storage::url($intervention->provider->estimations->first()->estimate) }}" target="_blank">
                        <button class="btn">Télécharger le devis</button>                                    
                    </a>
                        @if($intervention->statut_id != 5)
                        <button class="btn btn-error">Refuser le devis</button>    
                            <form method="POST", action="{{route('interventions.plan', $intervention->id)}}">
                                @csrf
                                
                                <button class="btn btn-warning mt-10 text-lg">Valider et payer</button>
                            </form>                          
                        @endif     
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>