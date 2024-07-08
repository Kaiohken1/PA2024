<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Estimer la valeur de son bien en un clic') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">

                    <div>
                        <x-input-label for="property_type" :value="__('Type de logement')" class="form-label" />
                        <select id="property_type" name="property_type" class="form-select select select-bordered w-full max-w-xs" wire:model="property_type">
                            <option value="1">Appartement</option>
                            <option value="1.15">Maison</option>
                            <option value="1.3">Gîte</option>
                        </select>
                        <x-input-error :messages="$errors->get('property_type')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label for="surface" :value="__('Surface (Au mètre carré)')" />
                        <x-text-input id="surface" class="form-input block mt-1 w-full" type="number" wire:model="surface" min="1" />
                        <x-input-error :messages="$errors->get('surface')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="location_rating" :value="__('Noté la localisation du bien')" />
                        <div class="rating">
                            <input type="radio" name="location_rating" class="mask mask-star" value="" checked hidden/>
                            @for ($i = 1; $i
                            <= 5; $i++) <input type="radio" name="location_rating" class="mask mask-star" value="{{ $i }}" wire:model="location_rating" />
                            @endfor
                        </div>
                    </div>

                    <div>
                        <x-input-label for="roomCount" :value="__('Nombre de pièces')" />
                        <x-text-input id="roomCount" class="form-input block mt-1 w-full" type="number" wire:model="roomCount" min="1" />
                        <x-input-error :messages="$errors->get('roomCount')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="aspect_rating" :value="__('Noté l\'aspect du bien')" />
                        <div class="rating">
                            <input type="radio" name="aspect_rating" class="mask mask-star" value="" checked hidden/>
                            @for ($i = 1; $i
                            <= 5; $i++) <input type="radio" name="aspect_rating" class="mask mask-star" value="{{ $i }}" wire:model="aspect_rating" />
                            @endfor
                        </div>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="tag_id">{{ __('Ajouter des tags') }}</label>
                        <select class="form-input chosen-select border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" multiple wire:model="tag_id" id="tag_id">
                            @foreach($tags as $tag)
                            <option value="{{$tag->id}}">{{$tag->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="guestCount" :value="__('Nombre de personnes')" />
                        <x-text-input id="guestCount" class="form-input block mt-1 w-full" type="number" wire:model="guestCount" min="1" />
                        <x-input-error :messages="$errors->get('guestCount')" class="mt-2" />
                    </div>

                    <x-primary-button class="ms-3 mt-5 ml-0" wire:click="calculatePrice">
                        {{ __('Commencer la simulation') }}
                    </x-primary-button>
                    @if ($priceEstimation)
                    <div class="mt-5">
                        <p>{{ __('Estimation du prix : ') }} {{ $priceEstimation }} {{ __('€') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>