<x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('property.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
    @csrf

    <div>
    <x-input-label for="name" :value="__('Type de logement')" class="form-label"/>
        <select name="property_type" class="form-select select select-bordered w-full max-w-xs">
            <option value="appartement">Appartement</option>
            <option value="house">Maison</option>
            <option value="gite">Gite</option>
        </select>
    </div>

    <div>
    <x-input-label for="name" :value="__('Type de location')" class="form-label"/>
        <select name="location_type" class="form-select select select-bordered w-full max-w-xs">
            <option value="full_property">Logement complet</option>
            <option value="guestroom">Chambre d'hôte</option>
        </select>
    </div>

    <div>
        <x-input-label for="name" :value="__('Titre')" class="form-label"/>
        <x-text-input id="name" class="form-input block mt-1 w-full" type="text" name="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="address" :value="__('Addresse')" />
        <x-text-input id="address" class="form-input block mt-1 w-full" type="text" name="address" />
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="city" :value="__('Ville')" />
        <x-text-input id="city" class="form-input block mt-1 w-full" type="text" name="city"/>
        <x-input-error :messages="$errors->get('city')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="city" :value="__('Code postal')" />
        <x-text-input id="postal_code" class="form-input block mt-1 w-full" type="text" name="postal_code" pattern="[0-9]{5}" oninput="timedValidation()"/>
        <span id="postal_code_error" class="text-red-500 text-sm hidden">Le code postal doit comporter 5 chiffres.</span>
        <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="surface" :value="__('Surface (Au mètre carré)')" />
        <x-text-input id="surface" class="form-input block mt-1 w-full" type="number" name="surface" min="1" />
        <x-input-error :messages="$errors->get('surface')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="guestCount" :value="__('Nombre de personnes')" />
        <x-text-input id="guestCount" class="form-input block mt-1 w-full" type="number" name="guestCount" min="1" />
        <x-input-error :messages="$errors->get('guestCount')" class="mt-2" />
    </div>


    <div>
        <x-input-label for="roomCount" :value="__('Nombre de pièces')" />
        <x-text-input id="roomCount" class="form-input block mt-1 w-full" type="number" name="roomCount" min="1" />
        <x-input-error :messages="$errors->get('roomCount')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="price" :value="__('Prix par nuit')" />
        <x-text-input id="price" class="form-input block mt-1 w-full" type="number" name="price"  min="1" />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea name="description" class="form-input block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm"></textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">Ajoutez vos images</label>
        <input class="file-input file-input-ghost w-full max-w-xs border-gray-300" id="image" type="file" name='image[]'
        multiple>
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>
    
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">Ajoutez des tags</label>
        <select class="select select-bordered w-full max-w-xs chosen-select border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" multiple name="tag_id[]" id=tag_id>
            @foreach($tags as $tag)
                <option value="{{$tag->id}}">{{$tag->name}}</option>
            @endforeach
        </select>
    </div>


    <x-primary-button class="ms-3 mt-5 ml-0">
        {{ __('Créer un appartement') }}
    </x-primary-button>
    </div>
</form>

<script>
    let timeout = null;

    function timedValidation() {
        clearTimeout(timeout);
        timeout = setTimeout(validatePostalCode, 500); 
    }

    function validatePostalCode() {
        const postalCodeInput = document.getElementById('postal_code');
        const postalCodeError = document.getElementById('postal_code_error');
        const pattern = /^[0-9]{5}$/;

        if (!pattern.test(postalCodeInput.value)) {
            postalCodeError.classList.remove('hidden');
        } else {
            postalCodeError.classList.add('hidden');
        }
    }
</script>