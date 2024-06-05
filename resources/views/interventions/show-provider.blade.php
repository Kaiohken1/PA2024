<x-provider-layout>
    @if (session('success'))
        <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600"
            role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600"
            role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-4">Intervention #{{$intervention->id}}</h2>
                    <p><strong>Client :</strong> {{ $intervention->user->name }} {{ $intervention->user->first_name }}</p>
                    <p><strong>Appartement :</strong>{{ $intervention->appartement->address }}</p>
                    <p><strong>Date d'intervention souhaitée:</strong> {{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y à H:i:s')}}</p>
                    <p><strong>Statut:</strong> {{ $intervention->statut->nom }}</p>
                    @if($intervention->comment)<p><strong>Commentaire du prestataire : </strong>{{$intervention->comment}}</p>@endif
                    @foreach ($intervention->service_parameters as $parameter)
                        <div class="flex">
                            @php
                            $version = Mpociot\Versionable\Version::find($parameter->pivot->parameter_version);
                            @endphp
                            <p class="mr-3"><strong>{{ $version->getModel()->name}}:</strong></p>
                            <p><strong>{{ $parameter->pivot->value }} </strong>
                        </div>
                    @endforeach

                    @if($intervention->description)<p><strong>Description:</strong> {{ $intervention->description }}</p>@endif
                
                    @if($intervention->fiche === "" || $intervention->fiche === NULL)
                    <form method="POST"action="{{route('contract.store-intervention')}}" enctype="multipart/form-data" class="mt-5">
                        @csrf
                        <x-input-label>Fiche d'intervention remplie</x-input-label>
                        <input type="file" name="fiche" class="file-input file-input-bordered file-input-warning w-full max-w-xs" />
                        @error('estimate')
                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                        @enderror

                        <div class="mt-4">
                            <p>{{__('Commentiare sur l\'intervention (facultatif)')}}</p>
                            <textarea name="comment" class="block mt-1 w-full"></textarea>
                        </div>

                        <input type="hidden" name="intervention_id" value="{{$intervention->id}}" />

                        <div class="mt-5">
                            <x-primary-button>envoyer</x-primary-button>
                        </div>
                    </form>
                    @else
                    <a href="{{Storage::url($intervention->fiche)}}" target="_blank"><strong><u>Télécharger la fiche d'intervention envoyée</strong></u></a>
                    @endif

                    <div class="mt-8">
                        <a href="{{route('contract.generate', Auth::user()->provider->id)}}"><strong><u>Télécharger le modèle de fiche d'intervention pour ce service</strong></u></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-provider-layout>