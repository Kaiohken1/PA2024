<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifer les informations de votre appartement') }}
        </h2>
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
    </x-slot>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mt-6">
                <div class="max-w-xl">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="post" action="{{ route('property.update', $appartement->id) }}" class="mt-6 space-y-6"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div>
                        <x-input-label for="property_type" :value="__('Type de logement')" class="form-label"/>
                            <select name="property_type" class="form-select select select-bordered w-full max-w-xs">
                                <option value="appartement">Appartement</option>
                                <option value="house">Maison</option>
                                <option value="gite">Gite</option>
                            </select>
                        </div>

                        <div>
                        <x-input-label for="location_type" :value="__('Type de location')" class="form-label"/>
                            <select name="location_type" class="form-select select select-bordered w-full max-w-xs">
                                <option value="full_property">Logement complet</option>
                                <option value="guestroom">Chambre d'hôte</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="name" :value="__('Titre')" />
                            <x-text-input id="name" class="form-input block mt-1 w-full" type="text" name="name"
                                :value="old('name', $appartement->name)" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('Addresse')" />
                            <x-text-input id="address" class="form-input block mt-1 w-full bg-gray-200" type="text" name="address"
                                :value="old('address', $appartement->address)" disabled/>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="city" :value="__('Ville')" />
                            <x-text-input id="city" class="form-input block mt-1 w-full bg-gray-200" type="text" name="city"
                                :value="old('city', $appartement->city)" disabled/>
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="postal_code" :value="__('Code postal')" />
                            <x-text-input id="postal_code" class="form-input block mt-1 w-full bg-gray-200" type="text" name="postal_code"
                                :value="old('postal_code', $appartement->postal_code)" disabled/>
                            <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="surface" :value="__('Surface (Au mètre carré)')" />
                            <x-text-input id="surface" class="form-input block mt-1 w-full" type="number" name="surface"
                                :value="old('surface', $appartement->surface)" min="1" />
                            <x-input-error :messages="$errors->get('surface')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="guestCount" :value="__('Nombre de personnes')" />
                            <x-text-input id="guestCount" class="form-input block mt-1 w-full" type="number" name="guestCount"
                                :value="old('guestCount', $appartement->guestCount)" min="1" />
                            <x-input-error :messages="$errors->get('guestCount')" class="mt-2" />
                        </div>


                        <div>
                            <x-input-label for="roomCount" :value="__('Nombre de pièces')" />
                            <x-text-input id="roomCount" class="form-input block mt-1 w-full" type="number" name="roomCount"
                                :value="old('roomCount', $appartement->roomCount)" min="1" />
                            <x-input-error :messages="$errors->get('roomCount')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Prix par nuit')" />
                            <x-text-input id="price" class="form-input block mt-1 w-full" type="number" name="price"
                                :value="old('price', $appartement->price)" min="1" />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea name="description" class="form-input form-textarea block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">{{ $appartement->description }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Ajouter des tags')" />
                            <select class="form-multiselect border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" multiple name="tag_id[]" id=tag_id>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($appartement->images->count() <= 15)
                            <div>
                                <x-input-label for="price" :value="__('Ajouter une nouveau image')" />
                                <input class="file-input file-input-ghost w-full max-w-xs border-gray-300" id="image" type="file" name='image[]'
                                    multiple>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>
                        @endif


                        <button class="btn btn-warning">
                            {{ __('Modifier mon appartement') }}
                        </button>
                    </form>

                    <div>
                        <x-input-label for="price" :value="__('Vos images activées')" class="mt-5" />
                        <livewire:appartement-images :appartementId="$appartement->id">
                        {{-- <div class="flex space-x-8">
                            @foreach ($appartement->images as $image)
                                <div class="relative">
                                    <img class="rounded-md mb-3 h-52" src="{{ Storage::url($image->image) }}" width="200px">
                                    <form method="POST" action="{{ route('property.setMainImg', $image) }}" class="absolute bottom-2 left-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="checkbox" name="is_main" value="1" {{ $image->is_main ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label for="is_main" class="text-white">Principale</label>
                                    </form>
                                    @if($appartement->images->count() > 1)
                                    <form method="POST" action="{{ route('property.destroyImg', $image) }}" class="absolute top-2 right-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M6 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zM5.293 9.293a1 1 0 011.414 0L10 13.586l3.293-3.293a1 1 0 111.414 1.414L11.414 15l3.293 3.293a1 1 0 01-1.414 1.414L10 16.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 15 5.293 11.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>                         --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
