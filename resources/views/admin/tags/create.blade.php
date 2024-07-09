<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ajouter un tag') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 border border-gray-700 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="POST" action="{{ route('tags.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nom')" class="text-white"/>
                            <x-text-input id="name" class="form-input block mt-1 w-full" type="text" name="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="valorisation_coeff" :value="__('Coefficiant de valorisation')" class="text-white"/>
                            <x-text-input id="valorisation_coeff" class="form-input block mt-1 w-full" type="text" name="valorisation_coeff"/>
                            <x-input-error :messages="$errors->get('valorisation_coeff')" class="mt-2" />
                        </div>
                        <button class="btn btn-warning mt-3 mr-3">{{ __('Créer un tag') }}</button>
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>