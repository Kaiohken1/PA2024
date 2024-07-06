<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Donner votre avis sur l\'appartement') }}
        </h2>
    </x-slot>

    <div class="py-8">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                            <form method="POST" action="{{ route('avis.store', $reservation->appartement_id) }}" enctype="multipart/form-data">
                                @csrf

                                <x-input-label for="rating_cleanness" :value="__('Notez la propretée du bien')" />
                                <div class="rating">
                                    <input type="radio" name="rating_cleanness" class="mask mask-star hidden" value="" checked/>
                                    <input type="radio" name="rating_cleanness" class="mask mask-star" value="1"/>
                                    <input type="radio" name="rating_cleanness" class="mask mask-star" value="2"/>
                                    <input type="radio" name="rating_cleanness" class="mask mask-star" value="3"/>
                                    <input type="radio" name="rating_cleanness" class="mask mask-star" value="4"/>
                                    <input type="radio" name="rating_cleanness" class="mask mask-star" value="5"/>
                                    <x-input-error :messages="$errors->get('rating_cleanness')" class="mt-2" />
                                </div>

                                <x-input-label for="rating_price_quality" :value="__('Notez le rapport qualité prix')" />
                                <div class="rating">
                                    <input type="radio" name="rating_price_quality" class="mask mask-star hidden" value="" checked/>
                                    <input type="radio" name="rating_price_quality" class="mask mask-star" value="1"/>
                                    <input type="radio" name="rating_price_quality" class="mask mask-star" value="2"/>
                                    <input type="radio" name="rating_price_quality" class="mask mask-star" value="3"/>
                                    <input type="radio" name="rating_price_quality" class="mask mask-star" value="4"/>
                                    <input type="radio" name="rating_price_quality" class="mask mask-star" value="5"/>
                                    <x-input-error :messages="$errors->get('rating_price_quality')" class="mt-2" />

                                </div>

                                <x-input-label for="rating_location" :value="__('Notez l\'emplacement du bien')" />
                                <div class="rating">
                                    <input type="radio" name="rating_location" class="mask mask-star hidden" value="" checked/>

                                    <input type="radio" name="rating_location" class="mask mask-star" value="1"/>
                                    <input type="radio" name="rating_location" class="mask mask-star" value="2"/>
                                    <input type="radio" name="rating_location" class="mask mask-star" value="3"/>
                                    <input type="radio" name="rating_location" class="mask mask-star" value="4"/>
                                    <input type="radio" name="rating_location" class="mask mask-star" value="5"/>
                                    <x-input-error :messages="$errors->get('rating_location')" class="mt-2" />

                                </div>

                                <x-input-label for="rating_communication" :value="__('Notez la communication des hôtes')" />
                                <div class="rating">
                                    <input type="radio" name="rating_communication" class="mask mask-star hidden" value="" checked/>
                                    <input type="radio" name="rating_communication" class="mask mask-star" value="1"/>
                                    <input type="radio" name="rating_communication" class="mask mask-star" value="2"/>
                                    <input type="radio" name="rating_communication" class="mask mask-star" value="3"/>
                                    <input type="radio" name="rating_communication" class="mask mask-star" value="4"/>
                                    <input type="radio" name="rating_communication" class="mask mask-star" value="5"/>
                                    <x-input-error :messages="$errors->get('rating_communication')" class="mt-2" />

                                </div>

                                <div>
                                    <x-input-label for="comment" :value="__('Commentaire')" />
                                    <x-text-input id="comment" class="form-input block mt-1 w-full" type="text" name="comment" />
                                    <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                                </div>

                                <input type="hidden" name="appartement_id" value="{{$reservation->appartement_id}}">
                                <input type="hidden" name="reservation_id" value="{{$reservation->id}}">

                                <x-primary-button class="ms-3 mt-5 ml-0">
                                    {{ __('Envoyer') }}
                                </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
