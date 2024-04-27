<x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('appart.store') }}" enctype="multipart/form-data">
    @csrf

    <div>
        <x-input-label for="name" :value="__('Titre')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="address" :value="__('Addresse')" />
        <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" />
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="surface" :value="__('Surface (Au mètre carré)')" />
        <x-text-input id="surface" class="block mt-1 w-full" type="number" name="surface" min="1" />
        <x-input-error :messages="$errors->get('surface')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="guestCount" :value="__('Nombre de personnes')" />
        <x-text-input id="guestCount" class="block mt-1 w-full" type="number" name="guestCount" min="1" />
        <x-input-error :messages="$errors->get('guestCount')" class="mt-2" />
    </div>


    <div>
        <x-input-label for="roomCount" :value="__('Nombre de pièces')" />
        <x-text-input id="roomCount" class="block mt-1 w-full" type="number" name="roomCount" min="1" />
        <x-input-error :messages="$errors->get('roomCount')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="price" :value="__('Prix par nuit')" />
        <x-text-input id="price" class="block mt-1 w-full" type="number" name="price"  min="1" />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea name="description" class="block mt-1 w-full"></textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">Ajoutez vos images</label>
        <input class="file-input w-full max-w-xs" id="image" type="file" name='image[]'
        multiple>
    <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>
    
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">Ajoutez des tags</label>
        <select class="chosen-select" multiple name="tag_id[]" id=tag_id>
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
