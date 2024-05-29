<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Information de facturation') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Modifez les informations de votre profil.") }}
        </p>
    </header>

    <form method="post" action="{{ route('billing.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="adresse" :value="__('Adresse')" />
            <x-text-input id="adresse" name="adresse" type="text" class="form-input mt-1 block w-full" :value="old('adresse', $user->adresse ?? '')" required autofocus autocomplete="adresse" />
            <x-input-error class="mt-2" :messages="$errors->get('adresse')" />
        </div>

        <div>
            <x-input-label for="code_postal" :value="__('Code Postal')" />
            <x-text-input id="code_postal" name="code_postal" type="text" class="form-input mt-1 block w-full" :value="old('code_postal', $user->code_postal ?? '')" required autofocus autocomplete="code_postal" />
            <x-input-error class="mt-2" :messages="$errors->get('code_postal')" />
        </div>

        <div>
            <x-input-label for="ville" :value="__('Ville')" />
            <x-text-input id="ville" name="ville" type="text" class="form-input mt-1 block w-full" :value="old('ville', $user->ville ?? '')" required autofocus autocomplete="ville" />
            <x-input-error class="mt-2" :messages="$errors->get('ville')" />
        </div>

        <div>
            <x-input-label for="display_city" :value="__('Afficher la ville')" />
            <input type="hidden" name="display_city" value="0">
            <input type="checkbox" id="display_city" name="display_city" class="toggle mt-1 block" 
                @if(old('display_city', $user->display_city)) checked @endif 
                value="1" autofocus autocomplete="display_city" />
            <x-input-error class="mt-2" :messages="$errors->get('display_city')" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'billing-information-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Sauvegardé.') }}</p>
            @endif
        </div>
    </form>
</section>
