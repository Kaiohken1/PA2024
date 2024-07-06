<x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('property.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf

    <div>
        <x-input-label for="name" :value="__('Type de logement')" class="form-label" />
        <x-input-label for="name" :value="__('Type de logement')" class="form-label" />
        <select name="property_type" class="form-select select select-bordered w-full max-w-xs">
            <option value="appartement">{{__('Appartement')}}</option>
            <option value="house">{{__('Maison')}}</option>
            <option value="gite">{{__('Gite')}}</option>
        </select>
    </div>

    <div>
        <x-input-label for="name" :value="__('Type de location')" class="form-label" />
        <x-input-label for="name" :value="__('Type de location')" class="form-label" />
        <select name="location_type" class="form-select select select-bordered w-full max-w-xs">
            <option value="full_property">{{__('Logement complet')}}</option>
            <option value="guestroom">{{__('Chambre d\'hôte')}}</option>
        </select>
    </div>

    <div>
        <x-input-label for="name" :value="__('Titre')" class="form-label" />
        <x-input-label for="name" :value="__('Titre')" class="form-label" />
        <x-text-input id="name" class="form-input block mt-1 w-full" type="text" name="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="address" :value="__('Adresse')" />
        <x-text-input id="autocomplete" class="form-input block mt-1 w-full" type="text" name="address" />
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="city" :value="__('Ville')" />
        <x-text-input id="city" class="form-input block mt-1 w-full" type="text" name="city" disabled/>
        <x-input-error :messages="$errors->get('city')" class="mt-2" />
        <input type="hidden" id="city-input" name="city">
    </div>

    <div>
        <x-input-label for="city" :value="__('Code postal')" />
        <x-text-input id="postal_code" class="form-input block mt-1 w-full" type="text" name="postal_code"
        disabled/>

        <input type="hidden" id="postal_code-input" name="postal_code">

    </div>

    <div>
        <x-input-label for="surface" :value="__('Surface (Au mètre carré)')" />
        <x-text-input id="surface" class="form-input block mt-1 w-full" type="number" name="surface"
            min="1" />
        <x-input-error :messages="$errors->get('surface')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="guestCount" :value="__('Nombre de personnes')" />
        <x-text-input id="guestCount" class="form-input block mt-1 w-full" type="number" name="guestCount"
            min="1" />
        <x-input-error :messages="$errors->get('guestCount')" class="mt-2" />
    </div>


    <div>
        <x-input-label for="roomCount" :value="__('Nombre de pièces')" />
        <x-text-input id="roomCount" class="form-input block mt-1 w-full" type="number" name="roomCount"
            min="1" />
        <x-input-error :messages="$errors->get('roomCount')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="price" :value="__('Prix par nuit')" />
        <x-text-input id="price" class="form-input block mt-1 w-full" type="number" name="price"
            min="1" />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea name="description"
            class="form-input block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm"></textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">{{__('Ajoutez vos
            images')}}</label>
        <input class="file-input file-input-ghost w-full max-w-xs border-gray-300" id="image" type="file"
            name='image[]' multiple>
        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>


    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">{{__('Ajoutez des
            tags')}}</label>
        <select
            class="select select-bordered w-full max-w-xs chosen-select border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm"
            multiple name="tag_id[]" id=tag_id>
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>


    <x-primary-button class="ms-3 mt-5 ml-0">
        {{ __('Créer un appartement') }}
    </x-primary-button>
</form>




<script type="text/javascript"
    src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_PLACES_API_KEY') }}&libraries=places"></script>
<script>
    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {
        var input = document.getElementById('autocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input, {
            componentRestrictions: { country: 'fr' },
            types: ['geocode']
        });
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            var city = '';
            var postalCode = '';

            place.address_components.forEach(function(component) {
                if (component.types.includes('locality')) {
                    city = component.long_name;
                }
                if (component.types.includes('postal_code')) {
                    postalCode = component.long_name;
                }
            });
            input.value = place.name;
            document.getElementById('city').value = city;
            document.getElementById('city-input').value = city;

            document.getElementById('postal_code').value = postalCode;
            document.getElementById('postal_code-input').value = postalCode;
        });

        input.addEventListener('input', function() {
            if (input.value === '') {
                document.getElementById('city').value = '';
                document.getElementById('postal_code').value = '';
            }
        });

        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    }
</script>
