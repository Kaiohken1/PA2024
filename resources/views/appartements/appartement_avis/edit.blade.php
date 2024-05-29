<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier votre avis sur l\'appartement') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <form class="form" method="POST" action="{{ route('avis.update', ['appartement' => $avis->appartement_id, 'avi' => $avis->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')                     

                

                            <x-input-label for="rating_cleanness" :value="__('Notez la propretée du bien')" />
                            <div class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating_cleanness" class="mask mask-star" value="{{ $i }}" {{ $avis->rating_cleanness == $i ? 'checked' : '' }}/>
                                @endfor
                            </div>

                            <x-input-label for="rating_price_quality" :value="__('Notez le rapport qualité prix')" />
                            <div class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating_price_quality" class="mask mask-star" value="{{ $i }}" {{ $avis->rating_price_quality == $i ? 'checked' : '' }}/>
                                @endfor
                            </div>

                            <x-input-label for="rating_location" :value="__('Notez l\'emplacement du bien')" />
                            <div class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating_location" class="mask mask-star" value="{{ $i }}" {{ $avis->rating_location == $i ? 'checked' : '' }}/>
                                @endfor
                            </div>

                            <x-input-label for="rating_communication" :value="__('Notez la communication des hôtes')" />
                            <div class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating_communication" class="mask mask-star" value="{{ $i }}" {{ $avis->rating_communication == $i ? 'checked' : '' }}/>
                                @endfor
                            </div>

                            <div>
                                <x-input-label for="comment" :value="__('Commentaire')" />
                                <x-text-input id="comment" class="form-input block mt-1 w-full" type="text" name="comment" :value="$avis->comment" />
                                <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                            </div>

                            <input type="hidden" name="appartement_id" value="{{ $avis->appartement_id }}">
                            <input type="hidden" name="reservation_id" value="{{ $avis->reservation_id }}">

                            <x-primary-button class="ms-3 mt-5 ml-0">
                                {{ __('Mettre à jour le commentaire') }}
                            </x-primary-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>