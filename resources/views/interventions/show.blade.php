<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

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
                    <h2 class="text-2xl font-bold mb-4 text-center">Intervention #{{$intervention->id}}</h2>

                    
                    <ul class="timeline flex justify-center mt-5">
                        <li>
                          <div class="timeline-start timeline-box">En attente</div>
                          <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-warning"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                          </div>
                          <hr class="bg-warning"/>                        
                        </li>
                        <li>
                        <hr @if($intervention->statut_id == 10 || $intervention->statut_id == 5 || $intervention->statut_id == 3 )class="bg-warning"@endif/>                        
                            <div class="timeline-start timeline-box">Devis reçu</div>
                          <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 @if($intervention->statut_id == 10 || $intervention->statut_id == 5 || $intervention->statut_id == 3 )text-warning @endif"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                          </div>
                          <hr @if($intervention->statut_id == 10 || $intervention->statut_id == 5 || $intervention->statut_id == 3 )class="bg-warning"@endif/>
                        </li>
                        <li>
                          <hr @if($intervention->statut_id == 5 || $intervention->statut_id == 3)class="bg-warning"@endif/>
                          <div class="timeline-start timeline-box">Payée</div>
                          <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 @if($intervention->statut_id == 5 || $intervention->statut_id == 3 )text-warning @endif"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                          </div>
                          <hr @if($intervention->statut_id == 5 || $intervention->statut_id == 3)class="bg-warning"@endif />
                        </li>
                        </li>
                        <li>
                          <hr @if($intervention->statut_id == 3)class="bg-warning"@endif/>
                          <div class="timeline-start timeline-box">Terminée</div>
                          <div class="timeline-middle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 @if($intervention->statut_id == 3)text-warning"@endif"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
                          </div>
                        </li>
                    </ul>

                    <p><strong>Client :</strong> {{ $intervention->user->name }} {{ $intervention->user->first_name }}</p>
                    <p><strong>Appartement :</strong>{{ $intervention->appartement->address }}</p>
                    <p><strong>Date d'intervention souhaitée:</strong> {{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y à H:i')}}</p>
                    @if($intervention->max_end_date)
                        <p><strong>Date de fin limite:</strong> {{\Carbon\Carbon::parse($intervention->max_end_date)->format('d/m/Y à H:i')}}</p>
                    @endif
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


                    @forelse ($intervention->estimations->where('provider_id', Auth::user()->provider->id) as $estimation)
                        @if($estimation->provider_id === Auth::user()->provider->id)
                            <div class="mt-5">
                                <p><strong>Tarif : </strong>{{$estimation->price}}€</p>
                                <p><strong>Commission conciergie : </strong>{{$estimation->commission}}€</p>
                                <p><strong>Date de fin prévue : </strong>{{\Carbon\Carbon::parse($estimation->end_time)->format('d/m/Y à H:i')}}</p>
                                <a href="{{ Storage::url($estimation->estimate) }}" target="_blank"><strong><u>Voir mon devis</u></strong></a>

                                @if($estimation->statut_id != 8)
                                    @if($intervention->statut_id != 5)
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
                                    @endif
                                @else
                                    <p>Votre devis a été refusé par le client pour la raison suivante :</p>{{$intervention->refusals->where('provider_id',Auth::user()->provider->id)->last()->refusal_reason}}
                                    <h2 class="mt-5 text-xl font-bold">Proposer un autre devis ?</h2>
                                    <form method="POST"action="{{route('estimate.update', $estimation->id)}}" enctype="multipart/form-data" class="mt-5">
                                        @csrf
                                        @method('PATCH')

                                        <div>
                                            <x-input-label for="end_time" :value="__('Date de fin')" />
                                            <x-text-input id="end_time"  class="input input-bordered input-warning w-full max-w-xs" type="datetime-local" name="end_time"/>
                                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                                        </div>
                                        @if($intervention->service->flexPrice)
                                            <div>
                                                <x-input-label for="price" :value="__('Tarif')" />
                                                <x-text-input id="price"  class="input input-bordered input-warning w-full max-w-xs" type="number" name="price" min=1/>
                                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                            </div>
                                        @else
                                            <input type="hidden" value="{{$intervention->service->price}}" name="price">

                                        @endif

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
                                <input type="text" id="end_time" name="end_time" placeholder="Sélectionnez une date"  class="input input-bordered input-warning w-full max-w-xs">
                                <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                            </div>
                            @if($intervention->service->flexPrice)
                                <div>
                                    <x-input-label for="price" :value="__('Tarif')" />
                                    <input id="price" type="number" name="price" min=1  class="input input-bordered input-warning w-full max-w-xs"/>
                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                </div>
                            @else
                                <input type="hidden" value="{{$intervention->service->price}}" name="price">
                            @endif

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


<script>
    var minDate = <?php echo json_encode(\Carbon\Carbon::parse($intervention->planned_date)->format('d.m.Y')); ?>;
    var maxDate = <?php echo $intervention->max_end_date ? json_encode(\Carbon\Carbon::parse($intervention->max_end_date)->format('d.m.Y')) : 'null'; ?>;
    var disabledDates = <?php echo json_encode($datesInBase); ?>;

    if (maxDate === 'null') {
        maxDate = null;
    }

    flatpickr("#end_time", {
        mode: "single",
        enableTime: true,
        dateFormat: "d-m-Y H:i",
        minDate: minDate,
        maxDate: maxDate,
        locale: "fr",
        disable: disabledDates,
    });
</script>