<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Devenir prestataire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(!App\Models\Service::All()->isEmpty())
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <form method="POST" action="{{ route('providers.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div>
                                <x-input-label for="name" :value="__('Nom de la société')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"/>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"/>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Numéro de téléphone')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" pattern="[0-9]{10}" :value="old('phone')"/>
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="address" :value="__('Adresse')" />
                                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea name="description" class="block mt-1 w-full" :value="old('description')"></textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="avatar" :value="__('Logo')" />
                                <input type="file" id="avatar" name="avatar" />
                                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                            </div>

                            <livewire:show-service-form />

                            <div>
                                <x-input-label for="provider_description" :value="__('Description de votre prestation')" />
                                <textarea name="provider_description" class="block mt-1 w-full" :value="old('provider_description')"></textarea>
                                <x-input-error :messages="$errors->get('provider_description')" class="mt-2" />
                            </div>

                            <x-primary-button class="ms-3 mt-5 ml-0">
                                {{ __('Devenir prestataire') }}
                            </x-primary-button>
                        </form>
                        @else
                        <p>Aucun service n'est disponible à l'inscription actuellement, merci de réessayer plus tard</p>
                        @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
