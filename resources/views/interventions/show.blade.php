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


                    @forelse ($intervention->estimations as $estimation)
                        @if($estimation->provider_id === Auth::user()->provider->id)
                            <div class="mt-5">
                                <p><strong>Tarif : </strong>{{$estimation->price}}€</p>
                                <p><strong>Date de fin prévue : </strong>{{\Carbon\Carbon::parse($intervention->end_time)->format('d/m/Y à H:i:s')}}</p>
                                <a href="{{ Storage::url($estimation->estimate) }}" target="_blank"><strong><u>Voir mon devis</u></strong></a>

                                @if($estimation->statut_id != 8)
                                    <form action="{{route('estimate.destroy', $estimation->id)}}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>

                                    <form method="POST"action="{{route('estimate.update', $estimation->id)}}" enctype="multipart/form-data" class="mt-5">
                                        @csrf
                                        @method('PATCH')
                                        <x-input-label>Devis</x-input-label>
                                        <input type="file" name="estimate" class="file-input file-input-bordered file-input-warning w-full max-w-xs" />
                                        @error('estimate')
                                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                                        @enderror
            
                                        <div class="mt-5">
                                            <x-primary-button>Modifier</x-primary-button>
                                        </div>
                                    </form>
                                @else
                                    <p>Votre devis a été refusé par le client pour la raison suivante :</p>{{$intervention->refusals->where('provider_id',Auth::user()->provider->id)->last()->refusal_reason}}
                                    <h2 class="mt-5 text-xl font-bold">Proposer un autre devis ?</h2>
                                    <form method="POST"action="{{route('estimate.update', $estimation->id)}}" enctype="multipart/form-data" class="mt-5">
                                        @csrf
                                        @method('PATCH')

                                        <div>
                                            <x-input-label for="end_time" :value="__('Date de fin')" />
                                            <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time"/>
                                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                                        </div>
                                        <div>
                                            <x-input-label for="price" :value="__('Tarif')" />
                                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" min=1/>
                                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                        </div>

                                        <x-input-label>Devis</x-input-label>
                                        <input type="file" name="estimate" class="file-input file-input-bordered file-input-warning w-full max-w-xs" />
                                        @error('estimate')
                                            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                                        @enderror
            
                                        <div class="mt-5">
                                            <x-primary-button>Modifier</x-primary-button>
                                        </div>
                                    </form>
                                @endif

                            </div>
                        @endif                        
                    @empty
                        <form method="POST"action="{{route('estimate.store')}}" enctype="multipart/form-data" class="mt-5">
                            @csrf

                            <div>
                                <x-input-label for="end_time" :value="__('Date de fin')" />
                                <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time"/>
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="price" :value="__('Tarif')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" min=1/>
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <x-input-label>Devis</x-input-label>
                            <input type="file" name="estimate" class="file-input file-input-bordered file-input-warning w-full max-w-xs" />
                            @error('estimate')
                                <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
                            @enderror

                            <input type="hidden" name="provider_id" value="{{Auth::user()->provider->id}}" />
                            <input type="hidden" name="intervention_id" value="{{$intervention->id}}" />

                            <div class="mt-5">
                                <x-primary-button>envoyer</x-primary-button>
                            </div>
                        </form>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-provider-layout>