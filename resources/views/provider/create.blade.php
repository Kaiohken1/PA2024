<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Devenir prestataire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(!App\Models\Service::All()->isEmpty())
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <form method="POST" action="{{ route('providers.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div>
                                <x-input-label for="name" :value="__('Nom de la société')" />
                                <x-text-input id="name" class="input input-bordered w-full" type="text" name="name" :value="old('name')"/>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="input input-bordered w-full" type="email" name="email" :value="old('email')"/>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="phone" :value="__('Numéro de téléphone')" />
                                <input id="phone" class="input input-bordered w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="username" placeholder="+33712345678" />
                                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{__('Entrez un numéro de téléphone qui respecte ce format')}}</p>
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="address" :value="__('Adresse')" />
                                <x-text-input id="address" class="input input-bordered w-full max-w-xl" type="text" name="address" :value="old('address')" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea name="description" class="input input-bordered w-full max-w-xl" :value="old('description')"></textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="avatar" :value="__('Logo')" />
                                <input type="file" id="avatar" name="avatar" class="file-input w-full max-w-xl" />
                                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                            </div>

                            <livewire:show-service-form />

                            {{-- <div>
                                <x-input-label for="provider_description" :value="__('Description de votre prestation')" />
                                <textarea name="provider_description" class="input input-bordered w-full max-w-xl" :value="old('provider_description')"></textarea>
                                <x-input-error :messages="$errors->get('provider_description')" class="mt-2" />
                            </div> --}}

                            <button class="btn btn-warning mt-5">
                                {{ __('Devenir prestataire') }}
                            </button>
                        </form>
                        @else
                        <p>Aucun service n'est disponible à l'inscription actuellement, merci de réessayer plus tard</p>
                        @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
