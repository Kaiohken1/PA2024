<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil de l\'utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="px-6 flex flex-col overflow-x-auto mb-3">
                        <div class="flex flex-row">
                            <div class="avatar">
                                <div class="w-24 rounded-full">
                                    <img src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://i0.wp.com/sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png?w=300&ssl=1'}}" />
                                </div>
                            </div>
                            <div class="flex flex-col ml-4">
                                <span class="text-3xl font-extrabold text-left">{{ $user->first_name }} {{ $user->name }}</span>
                                <span class="text-base font-bold text-left">{{ $user->display_city == 1 ? $user->ville : '' }}</span>
                                <span class="text-base font-bold text-left">Membre depuis {{ Illuminate\Support\Carbon::parse($user->created_at)->translatedFormat('F Y') }}</span>
                            </div>
                        </div>
                        <div class="flex flex-row mt-4">
                            <span>{{ $user->bio }}</span>
                        </div>
                    </div>

                    <!-- Affichage des informations d'abonnement -->
                    @if ($subscriptionName)
                        <div class="px-6 mt-6">
                            <h3 class="text-2xl font-extrabold">Abonnement</h3>
                            <p class="text-lg">Type d'abonnement : <span class="font-bold">{{ $subscriptionName }}</span></p>
                            <p class="text-lg">Prestations gratuites restantes : <span class="font-bold">{{ $freeServicesRemaining }}</span></p>
                            @if ($freeServicesRemaining == 0 && $nextFreeServiceTime)
                                <p class="text-lg">Temps avant le prochain renouvellement : <span class="font-bold">{{ $nextFreeServiceTime }}</span></p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="w-full bg-white shadow-md rounded my-4">
                <h1 class="py-3 px-6 text-2xl font-extrabold">Avis des voyageurs</h1>
                <div>
                    @forelse ($userAvis as $avis)
                        <div class="px-6 flex flex-col border-b-2 border-grey overflow-x-auto mb-3">
                            <div class="flex flex-row items-center">
                                <div class="avatar">
                                    <div class="w-12 rounded-full">
                                        <a href="{{ route('users.show', ['user' => $avis->sender_user_id]) }}">
                                            <img src="{{ $avis->sendUser->avatar ? Storage::url($avis->sendUser->avatar) : 'https://i0.wp.com/sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png?w=300&ssl=1' }}" />
                                        </a>
                                    </div>
                                </div>
                                <a href="{{ route('users.show', ['user' => $avis->sender_user_id]) }}" class="h-1/2 ml-4">
                                    <span class="text-lg font-extrabold">{{ $avis->sendUser->first_name }} {{ $avis->sendUser->name }}</span>
                                </a>
                            </div>
                            <div class="flex flex-row items-center mt-2">
                                <div class="py-1 rating rating-sm">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <input type="radio" class="mask mask-star cursor-default" value="{{ $i }}" disabled {{ $avis->rating == $i ? 'checked' : '' }} />
                                    @endfor
                                </div>
                                <span class="ml-2">{{ Illuminate\Support\Carbon::parse($avis->created_at)->translatedFormat('F Y') }}</span>
                                @if($avis->sender_user_id == auth()->user()->id)
                                    <div class="ml-4">
                                        <form action="{{ route('users.avis.destroy', ['user' => $avis->receiver_user_id, 'avis' => $avis->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
                                                <x-far-trash-alt class="w-6 h-6"/>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="ml-2">
                                        <a href="{{ route('users.avis.edit', ['user' => $avis->receiver_user_id, 'avis' => $avis->id]) }}">
                                            <x-far-edit class="w-6 h-6"/>
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
        </div>
    </div>
</x-app-layout>
