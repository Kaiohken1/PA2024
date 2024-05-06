<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl  leading-tight text-white">
            {{ __('Ajouter un nouveau service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-grey-800 border shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form action="{{ route('services.store') }}" method="post">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Nom du service')" class="text-white" />
                            <input id="name" name="name" type="text" class="input input-bordered w-full max-w-xs"/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Prix du service')" class="text-white" />
                            <input type="number" id="price" name="price" class="input input-bordered w-full max-w-xs" min="1" />                            
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="flexPrice" :value="__('Tarif évolutif')" class="text-white" />
                            <input type="checkbox" id="flexPrice" name="flexPrice">
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description du service')" class="text-white" />
                            <textarea id="description" name="description" class="textarea mb-5"></textarea>
                        </div>

                        <h1 class="text-white text-2xl font-bold">Ajouter des paramètres aux service</h1>
                        <livewire:dynamic-input />
                        
                        <div class="flex items-center gap-4 mt-5">
                            <button class="btn btn-active btn-neutral">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>