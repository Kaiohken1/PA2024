<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Profil de l\'utilisateur') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-white">
        <div class="py-3">

            <div class="p-6 bg-slate-950 shadow sm:rounded-lg">


                <div class="flex flex-col mb-3">
                    <div class="flex justify-between">
                        <div class="flex items-center">
                            <div class="avatar mr-3">
                                <div class="w-24 rounded-full">
                                    <img src="{{ $provider->avatar ? Storage::url($provider->avatar) : 'https://i0.wp.com/sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png?w=300&ssl=1'}}" />
                                </div>
                            </div>
                            <div class=" flex flex-col">
                                <span class="text-3xl font-extrabold text-left">{{ $provider->name }}</span>
                                <span class="text-base font-bold">{{ $provider->user->display_city == 1 ? $provider->user->ville : '' }}</span>
                                <span class="text-base font-bold">{{ __('Membre depuis') }} {{ Illuminate\Support\Carbon::parse($provider->user->created_at)->translatedFormat('F Y') }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col text-right">
                            <span class="text-3xl font-extrabold">{{ __('Information du prestataire') }}</span>
                            <span class="text-base font-bold">{{ $provider->user->name }} {{ $provider->user->first_name }}</span>
                            <span class="text-base font-bold">{{ $provider->email }}</span>
                            <span class="text-base font-bold">+{{ $provider->phone }}</span>
                            <span class="text-base font-bold">{{ $provider->address }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col mb-3">
                    <div class="flex justify-between">
                        <div class="flex items-center">

                            <div class=" flex flex-col">
                                <h2 class="text-2xl font-bold mb-4">Service proposé : {{ $service->name }}</h2>
                                @if($service->pivot->price_scale)<a href="{{ Storage::url($service->pivot->price_scale) }}" class="mt-2 btn max-w-xs" target="_blank"><strong>Barème appliqué au service</strong></a>@endif
                                <a href="{{ Storage::url($provider->iban) }}" class="mt-2 btn max-w-xs" target="_blank"><strong>Coordonées bancaires</strong></a>
                            </div>
                        </div>
                        <div class="flex flex-col text-right">
                            <h3 class="text-2xl font-bold mb-4">Documents envoyés : </h3>
                            @foreach ($provider->documents as $document)
                            <p>{{$document->name}} :
                                <a href="{{Storage::url($document->pivot->document)}}" class="btn">Télécharger</a>
                            </p>
                            @endforeach
                            @if ($provider->statut === 'En attente' && Auth::user()->isAdmin())
                            <div class="p-6 text-white flex">
                                <form action="{{ route('admin.providers.validate', $provider) }}" method="POST" class="mr-5">
                                    @csrf
                                    @method('patch')
                                    <button class="btn btn-warning">
                                        Valider le prestataire
                                    </button>
                                </form>

                                <form action="{{ route('admin.providers.destroy', $provider) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-error">
                                        Refuser le prestataire
                                    </button>
                                </form>
                            </div>
                            @endif
                            <div class="p-6 text-white">
                                <p>Statut : <strong>{{ $provider->statut }}</strong></p>
                            </div>
                        </div>

                    </div>
                    <h4 class="text-2xl font-bold mb-4">{{ __('Description : ') }}</h4>
                    <span>{{ $provider->description }}</span>
                </div>
            </div>
            <div class="w-full bg-slate-950 shadow-md sm:rounded-lg my-4">
                <h1 class="py-3 px-6 text-2xl font-extrabold">Avis des voyageurs</h1>
                <div>

                    @forelse ($provider->user->receivedAvis as $avis)
                    <div class="px-6 flex flex-col overflow-x-auto mb-3">
                        <div class="flex flex-row">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <a href="{{ route('users.show', ['user' => $avis->sender_user_id]) }}">
                                        <img src="{{ $avis->sendUser->avatar ? Storage::url($avis->sendUser->avatar) : 'https://i0.wp.com/sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png?w=300&ssl=1' }}" />
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('users.show', ['user' => $avis->sender_user_id]) }}" class="h-1/2">
                                <span class="text-lg font-extrabold px-2 text-left">{{ $avis->sendUser->first_name }} {{ $avis->sendUser->name }}</span>
                            </a>
                        </div>
                        <div class="flex flex-row">
                            <div class="py-1 rating rating-sm">
                                @for ($i = 1; $i
                                <= 5; $i++) <input type="radio" class="mask mask-star cursor-default bg-white" value="{{ $i }}" disabled {{ $avis->rating == $i ? 'checked' : '' }} />
                                @endfor
                            </div>
                            <span>{{ Illuminate\Support\Carbon::parse($avis->created_at)->translatedFormat('F Y') }}</span>
                            @if($avis->sender_user_id == auth()->user()->id)
                            <div>
                                <form action="{{ route('users.avis.destroy', ['user' => $avis->receiver_user_id, 'avis' => $avis->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
                                        <x-far-trash-alt class="w-6 h-6" />
                                    </button>
                                </form>
                            </div>
                            <div class="px-2">
                                <a href="{{ route('users.avis.edit', ['user' => $avis->receiver_user_id, 'avis' => $avis->id]) }}">
                                    <x-far-edit class="w-6 h-6" />
                                </a>
                            </div>
                            @endif
                        </div>
                        <span class="py-3 text-left">{{ $avis->comment }}</span>
                    </div>
                    @empty
                    <span class="px-6 text-left text-lg font-extrabold">Aucun commentaire</span>
                    @endforelse
                </div>
            </div>



</x-admin-layout>