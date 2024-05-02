<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter un nouveau service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl text-gray-800">
                    <form action="{{ route('services.store') }}" method="post">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Nom du service')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="flex items-center gap-4 mt-5">
                            <x-primary-button>Valider</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>