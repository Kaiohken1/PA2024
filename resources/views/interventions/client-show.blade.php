<x-app-layout>
    <x-session-statut></x-session-statut>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div>
                    <h1 class="text-2xl font-bold flex justify-center">{{__('Intervention')}} #{{$intervention->id}}</h1>

                    <ul class="timeline flex justify-center mt-5">
                        <li>
                          <div class="timeline-start timeline-box">{{__('En attente')}}</div>
                          <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-warning"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                          </div>
                          <hr class="bg-warning"/>                        
                        </li>
                        <li>
                        <hr @if($intervention->statut_id == 10 || $intervention->statut_id == 5 || $intervention->statut_id == 3 )class="bg-warning"@endif/>                        
                            <div class="timeline-start timeline-box">{{__('Devis reçu')}}</div>
                          <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 @if($intervention->statut_id == 10 || $intervention->statut_id == 5 || $intervention->statut_id == 3 )text-warning @endif"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                          </div>
                          <hr @if($intervention->statut_id == 10 || $intervention->statut_id == 5 || $intervention->statut_id == 3 )class="bg-warning"@endif/>
                        </li>
                        <li>
                          <hr @if($intervention->statut_id == 5 || $intervention->statut_id == 3)class="bg-warning"@endif/>
                          <div class="timeline-start timeline-box">{{__('Payée')}}</div>
                          <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 @if($intervention->statut_id == 5 || $intervention->statut_id == 3 )text-warning @endif"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                          </div>
                          <hr @if($intervention->statut_id == 5 || $intervention->statut_id == 3)class="bg-warning"@endif />
                        </li>
                        </li>
                        <li>
                          <hr @if($intervention->statut_id == 3)class="bg-warning"@endif/>
                          <div class="timeline-start timeline-box">{{__('Terminée')}}</div>
                          <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 @if($intervention->statut_id == 3)text-warning"@endif"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                          </div>
                        </li>
                    </ul>

                    <h2 class="text-lg font-bold mt-5 border-b">{{__('Détails de l\'intervention')}}</h2>

                    <p class="text-lg"><label class="text-yellow-800 pr-10">{{__('Service')}}</label> {{$intervention->service->name}} </p>

                    <p class="text-lg"><label class="text-yellow-800 pr-10">{{__('Prestataire')}}</label> @if($intervention->provider) {{$intervention->provider->name}} @else {{__('À définir')}} @endif</p>

                    @foreach($intervention->service_parameters as $parameter)
                        @php
                        $version = Mpociot\Versionable\Version::find($parameter->pivot->parameter_version);
                        @endphp
                        <p class="text-lg"><label class="text-yellow-800 pr-10">{{$version->getModel()->name}}</label> {{$parameter->pivot->value}} </p>
                    @endforeach

                    <p class="text-lg"><label class="text-yellow-800 pr-10">{{__('Date et heure de début')}}</label> {{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y à H:i')}} </p>

                    @if($intervention->planned_end_date)
                    <p class="text-lg"><label class="text-yellow-800 pr-10">{{__('Date et heure de fin')}}</label> {{\Carbon\Carbon::parse($intervention->planned_end_date)->format('d/m/Y à H:i')}} </p>
                    @endif

                    <p class="text-lg"><label class="text-yellow-800 pr-10">{{__('Montant à payer')}}</label> @if($intervention->price) <strong>{{$intervention->price + ($intervention->price*0.20)}}€</strong> @else{{__('À définir')}}  @endif</p>
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
                        
                        @endif
                        @if($intervention->statut_id == 3)
                        <button class="btn mt-3 max-w-fit">
                            <a href="{{ route('users.avis.create', ['user' => $intervention->provider->user_id]) }}">Donner son avis sur le prestataire</a>
                        </button>
                        @endif
                        @if(($intervention->statut_id !== 5 && $intervention->statut_id !== 3) && $intervention->estimations->where('statut_id', 9)->first())
                            <button class="btn btn-error" onclick="document.getElementById('my_modal_1').showModal()">{{__('Refuser le devis')}}</button> 
                            @endif
                    @endif  
                </div>
 
                    @if($intervention->provider) 

                        @if(($intervention->statut_id !== 5 && $intervention->statut_id !== 3) && $intervention->estimations->where('statut_id', 9)->first())
                        <form method="POST", action="{{route('interventions.checkout', $intervention->id)}}">
                            @csrf
                                
                            <button class="btn btn-warning mt-10 text-lg">Valider et payer</button>
                        </form>                          
                        @endif     
                    @endif

                @if($intervention->statut_id !== 5 && $intervention->statut_id !== 3 && $intervention->statut_id !== 4)
                    <div>
                        <form method="POST", action="{{route('interventions.destroy', ['id' => $intervention->appartement->id, 'intervention' => $intervention->id])}}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-error mt-10">{{__('Annuler ma demande')}}</button>
                        </form> 
                    </div>  
                @endif
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
                <button type="submit" class="btn btn-warning w-full mb-4">{{__('Soumettre')}}</button>
            </form>
            <div class="modal-action">
                <button class="btn w-full" onclick="document.getElementById('my_modal_1').close()">{{__('Fermer')}}</button>
            </div>
        </div>
    </dialog>
    @endif
</x-app-layout>
