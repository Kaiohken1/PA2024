<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un tag') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="POST" action="{{ route('tags.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nom')" />
                            <x-text-input id="name" class="form-input block mt-1 w-full" type="text" name="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="valorisation_coeff" :value="__('Coefficiant de valorisation')" />
                            <x-text-input id="valorisation_coeff" class="form-input block mt-1 w-full" type="text" name="valorisation_coeff"/>
                            <x-input-error :messages="$errors->get('valorisation_coeff')" class="mt-2" />
                        </div>

                        <x-primary-button class="ms-3 mt-5 ml-0">
                            {{ __('Créer un tag') }}
                        </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>