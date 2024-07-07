<x-app-layout>
    <x-session-statut></x-session-statut>
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

                    <p class="text-lg"><label class="text-yellow-800 pr-10">Montant à payer</label> @if($intervention->price) <strong>{{$intervention->price + ($intervention->price*0.20)}}€</strong> @else À définir @endif</p>
                </div>

                <div class="mt-4 flex flex-col ">
                    @if($intervention->provider) 
                    
                        <button class="btn max-w-fit">
                            <a class="" href="{{ Storage::url($intervention->provider->estimations->first()->estimate) }}" target="_blank">Télécharger le devis</a>
                        </button>                                    
                    
                        @if($intervention->statut_id == 5 || $intervention->statut_id == 3)
                        <button class="btn mt-3 max-w-fit">
                            <a href="{{route('interventions.generate', $intervention->id)}}">Télécharger la facture</a>
                        </button> 
                        
                        <button class="btn mt-3 max-w-fit">
                            <a href="{{route('interventions.chat', ['intervention' => $intervention->id, 'user' => $intervention->provider->user->id])}}">Accèder au chat</a>
                        </button>
                        @endif
                        @if($intervention->statut_id == 3)
                        <button class="btn mt-3 max-w-fit">
                            <a href="{{ route('users.avis.create', ['user' => $intervention->provider->user_id]) }}">Donner son avis sur le prestataire</a>
                        </button>
                        @endif

                        @if(($intervention->statut_id !== 5 && $intervention->statut_id !== 3) && $intervention->estimations->where('statut_id', 9)->first())

                        <!-- Bouton pour ouvrir la modal -->
                        <button class="btn btn-error" onclick="document.getElementById('my_modal_1').showModal()">Refuser le devis</button>    

                        <form method="POST", action="{{route('interventions.checkout', $intervention->id)}}">
                            @csrf
                                
                            <button class="btn btn-warning mt-10 text-lg">Valider et payer</button>
                        </form>                          
                        @endif     
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($intervention->estimations->where('statut_id', 9)->first())
    <dialog id="my_modal_1" class="modal fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75">
        <div class="modal-box bg-white p-6 rounded-lg w-1/3">
            <h3 class="font-bold text-lg mb-4">Raison du refus</h3>
            <form action="{{route('interventions.refused', $intervention->estimations->where('statut_id', 9)->first()->id)}}" method="POST">
                @csrf
                <textarea class="w-full p-2 border rounded mb-4" rows="4" placeholder="Veuillez indiquer la raison du refus..." name="refusal"></textarea>
                <button type="submit" class="btn btn-warning w-full mb-4">Soumettre</button>
            </form>
            <div class="modal-action">
                <button class="btn w-full" onclick="document.getElementById('my_modal_1').close()">Fermer</button>
            </div>
        </div>
    </dialog>
    @endif
</x-app-layout>
