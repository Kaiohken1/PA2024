<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Estimer la valeur de son bien en un clic') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="post" action="{{ route('estimation.result') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="surface" :value="__('Surface (Au mètre carré)')" />
                            <x-text-input id="surface" class="form-input block mt-1 w-full" type="number" name="surface" min="1" />
                            <x-input-error :messages="$errors->get('surface')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="surface" :value="__('Noté la localisation du bien')" />
                            <div class="rating">
                                <input type="radio" name="location_rating" class="mask mask-star" value="1" />
                                <input type="radio" name="location_rating" class="mask mask-star" value="2" />
                                <input type="radio" name="location_rating" class="mask mask-star" value="3" />
                                <input type="radio" name="location_rating" class="mask mask-star" value="4" />
                                <input type="radio" name="location_rating" class="mask mask-star" value="5" />
                            </div>
                        </div>


                        <div>
                            <x-input-label for="roomCount" :value="__('Nombre de pièces')" />
                            <x-text-input id="roomCount" class="form-input block mt-1 w-full " type="number" name="roomCount" min="1" />
                            <x-input-error :messages="$errors->get('roomCount')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="surface" :value="__('Noté l\'aspect du bien')" />
                            <div class="rating">
                                <input type="radio" name="aspect_rating" class="mask mask-star" value="1" />
                                <input type="radio" name="aspect_rating" class="mask mask-star" value="2" />
                                <input type="radio" name="aspect_rating" class="mask mask-star" value="3" />
                                <input type="radio" name="aspect_rating" class="mask mask-star" value="4" />
                                <input type="radio" name="aspect_rating" class="mask mask-star" value="5" />
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">Ajoutez des tags</label>
                            <select class="form-input chosen-select border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" multiple name="tag_id[]" id=tag_id>
                                @foreach($tags as $tag)
                                <option value="{{$tag->id}}">{{$tag->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="guestCount" :value="__('Nombre de personnes')" />
                            <x-text-input id="guestCount" class="form-input block mt-1 w-full" type="number" name="guestCount" min="1" />
                            <x-input-error :messages="$errors->get('guestCount')" class="mt-2" />
                        </div>


                        <x-primary-button class="ms-3 mt-5 ml-0">
                            {{ __('Commencer la simulation') }}
                        </x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>