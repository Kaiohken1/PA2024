<x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('tag.store') }}" enctype="multipart/form-data">
    @csrf

    <div>
        <x-input-label for="name" :value="__('Nom')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <x-primary-button class="ms-3 mt-5 ml-0">
        {{ __('Créer un tag') }}
    </x-primary-button>
    </div>
</form>
