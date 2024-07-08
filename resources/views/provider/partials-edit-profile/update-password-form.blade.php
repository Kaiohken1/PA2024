<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Modifiez votre mot de passe') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Assurez-vous d\'utiliser un mot de passe fiable pour garantir la sécurité de votre compte') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" class="form-label">{{ __('Mot de passe actuel') }}</x-input-label>
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-input mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" class="form-label">{{ __('Nouveau mot de passe') }}</x-input-label>
            <x-text-input id="update_password_password" name="password" type="password" class="form-input mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" class="form-label">{{ __('Confirmez le nouveau mot de passe') }}</x-input-label>
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'password-updated')
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