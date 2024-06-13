<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl  leading-tight text-white">
            {{ __('Ajouter un nouveau service') }}
        </h2>

        @if (session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600"
                role="alert">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600"
                role="alert">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-grey-800 border shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if(!App\Models\Document::all()->isEmpty())
                        <form action="{{ route('services.store') }}" method="post">
                            @csrf
                            <div>
                                <x-input-label for="name" :value="__('Nom du service')" class="text-white" />
                                <input id="name" name="name" type="text"
                                    class="input input-bordered w-full max-w-xs" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="price" :value="__('Prix du service')" class="text-white" />
                                <input type="number" id="price" name="price"
                                    class="input input-bordered w-full max-w-xs" min="1" />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <div>
                                <x-input-label for="flexPrice" :value="__('Tarif évolutif')" class="text-white" />
                                <input type="checkbox" id="flexPrice" name="flexPrice">
                            </div>

                            <div>
                                <x-input-label for="role" :value="__('Role')" class="text-white mt-2" />
                                    <select name="role_id" id="role">
                                        <option value="">Selectionez un rôle</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->nom }}</option>
                                        @endforeach
                                    </select>
                                <x-input-error class="mt-2" :messages="$errors->get('role_id')" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Description du service')" class="text-white" />
                                <textarea id="description" name="description" class="textarea mb-5"></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div>
                                <select id="category_id"  name ="category_id"
                                class="shadow-sm border-0 focus:outline-none p-3 block sm:text-sm border-gray-300 rounded-md mb-2">
                                <option value="">Sélectionnez un type de catégorie</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <h1 class="text-white text-2xl font-bold">Ajouter des paramètres aux service</h1>

                            <livewire:dynamic-input />

                            @if ($errors->any())
                                <div class="text-red-500">
                                    <ul>
                                        @foreach ($errors->keys() as $errorKey)
                                            @if (Str::startsWith($errorKey, 'input_'))
                                                @foreach ($errors->get($errorKey) as $errorMessage)
                                                    <li>{{ $errorMessage }}</li>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <h1 class="text-white text-2xl font-bold">Ajouter des documents requis pour l'inscription au service</h1>

                            <livewire:dynamic-select />

                            @if ($errors->any())
                            <div class="text-red-500">
                                <ul>
                                    @foreach ($errors->keys() as $errorKey)
                                        @if (Str::startsWith($errorKey, 'documentsId'))
                                            @foreach ($errors->get($errorKey) as $errorMessage)
                                                <li>{{ __('Document requis') }}</li>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="flex items-center gap-4 mt-5">
                                <button class="btn btn-active btn-neutral">Valider</button>
                            </div>
                        </form>
                    @else
                        <p class="text-white">Avant de pouvoir créer un nouveau service, <a href="{{route('documents.create')}}" class="underline">merci de définir au moins un type de document qui sera requis pour les inscriptions de prestataires</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    </x-app-layout>
