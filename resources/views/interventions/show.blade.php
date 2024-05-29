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
                    <p><strong>Date d'intervention souhaitée:</strong> {{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y H:i:s')}}</p>
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


                    @foreach ($intervention->estimations as $estimation)
                    @if($estimation->provider_id === Auth::user()->provider->id)
                        <div>
                            <a href="{{ Storage::url($estimation->estimate) }}" target="_blank"><strong><u>Voir mon devis</u></strong></a>
                            <a href="" class="btn btn-info">Modifier</a>
                            <form action="" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    @endif
                @endforeach


                    <form method="POST"action="{{route('estimate.store')}}" enctype="multipart/form-data" class="mt-5">
                        @csrf
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
                </div>
            </div>
        </div>
    </div>
</x-provider-layout>
