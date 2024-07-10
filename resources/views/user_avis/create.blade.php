<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Donner votre avis sur le prestataire') }}
        </h2>

    </x-slot>

    <div class="py-8">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                            <form method="POST" action="{{ route('users.avis.store', $receive_user_id) }}" enctype="multipart/form-data">
                                @csrf

                                <x-input-label for="rating" :value="__('Notez le prestataire')" />
                                <div class="rating">
                                    <input type="radio" name="rating" class="mask mask-star" value="" checked hidden/>
                                    <input type="radio" name="rating" class="mask mask-star" value="1"/>
                                    <input type="radio" name="rating" class="mask mask-star" value="2"/>
                                    <input type="radio" name="rating" class="mask mask-star" value="3"/>
                                    <input type="radio" name="rating" class="mask mask-star" value="4"/>
                                    <input type="radio" name="rating" class="mask mask-star" value="5"/>
                                </div>

                                <div>
                                    <x-input-label for="comment" :value="__('Commentaire')" />
                                    <x-text-input id="comment" class="form-input block mt-1 w-full" type="text" name="comment" />
                                    <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                                </div>

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
