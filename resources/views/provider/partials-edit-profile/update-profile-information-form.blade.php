<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations générales') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Modifiez les informations de votre profil.") }}
        </p>
    </header>

    <form method="post" action="{{ route('provider.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" class="form-label">{{ __('Nom') }}</x-input-label>
            <x-text-input id="name" name="name" type="text" class="form-input mt-1 block w-full" :value="old('name', $user->provider->name)" disabled required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" class="form-label">{{ __('Email') }}</x-input-label>
            <x-text-input id="email" name="email" type="email" class="form-input mt-1 block w-full" :value="old('email', $user->provider->email)" disabled required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Votre adresse email n\'est pas vérifiée.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Téléphone')" />
            <x-text-input id="phone" name="phone" type="text" class="form-input mt-1 block w-full" :value="old('phone', $user->provider->phone ?? '')" required autofocus autocomplete="phone" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Adresse')" />
            <x-text-input id="address" name="address" type="text" class="form-input mt-1 block w-full" :value="old('address', $user->provider->address ?? '')" required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="description" :value="__('Description')" />
            <textarea name="description" class="form-input form-textarea block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">{{ $user->provider->description }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="avatar" class="form-label">{{ __('Avatar') }}</x-input-label>
            <div class="avatar flex mb-3 mt-3">
                <div class="w-32 rounded">
                    <img src="{{ $user->provider->avatar ? Storage::url($user->provider->avatar) : 'https://i0.wp.com/sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png?w=300&ssl=1'}}" alt="Avatar">
                </div>
            </div>
            <input type="file" name="avatar" id="avatar" class="file-input file-input-ghost w-full max-w-xs border-gray-300">
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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
