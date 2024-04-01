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
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="POST" action="{{ route('prestataire.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nom de la société')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('Addresse')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea name="description" class="block mt-1 w-full"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="avatar" :value="__('Avatar')" />
                            <input type="file" id="avatar" name="avatar" />
                            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
                        </div>


                        <div>
                            <x-input-label for="service" :value="__('Service proposé')" />
                            <select name="service_id" id="service_id">
                                <option value="default" disabled>Selectionnez un service</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}">
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('service_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Tarif')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>


                        <div>
                            <x-input-label for="flexPrice" :value="__('Tarif évolutif')" />
                            <input type="checkbox" id="flexPrice" name="flexPrice">
                        </div>

                        <div>
                            <x-input-label for="habilitationImg" :value="__('Habilitation')" />
                            <input type="file" id="habilitationImg" name="habilitationImg" />
                            <x-input-error :messages="$errors->get('habilitationImg')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="provider_description" :value="__('Description de votre prestation')" />
                            <textarea name="provider_description" class="block mt-1 w-full"></textarea>
                            <x-input-error :messages="$errors->get('provider_description')" class="mt-2" />
                        </div>

                        <x-primary-button class="ms-3 mt-5 ml-0">
                            {{ __('Devenir prestataire') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
