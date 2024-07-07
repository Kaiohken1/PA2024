<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl  leading-tight text-white">
            {{ __('Editer le service')  }} {{$service->name}}
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
                    <form action="{{ route('services.update', $service) }}" method="post">
                        @csrf
                        @method('patch')
                        <div>
                            <x-input-label for="name" :value="__('Nom du service')" class="text-white" />
                            <x-text-input id="name" name="name" type="text" class="input input-bordered w-full max-w-xs" :value="old('name', $service->name)"/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Prix du service')" class="text-white" />
                            <x-text-input type="number" id="price" name="price" class="input input-bordered w-full max-w-xs" min="1" :value="old('name', $service->price)" />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>

                        <div>
                            <x-input-label for="flexPrice" :value="__('Tarif évolutif')" class="text-white" />
                            <input type="checkbox" id="flexPrice" name="flexPrice" value="{{$service->flexPrice}}">
                        </div>

                        {{-- <div>
                            <x-input-label for="role" :value="__('Role')" class="text-white mt-2" />
                                <select name="role_id" id="role">
                                    <option value="">Selectionez un rôle</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @if($role->id === $service->role_id) selected @endif>{{ $role->nom }}</option>
                                    @endforeach
                                </select>
                            <x-input-error class="mt-2" :messages="$errors->get('role_id')" />
                        </div> --}}

                        <div>
                            <x-input-label for="description" :value="__('Description du service')" class="text-white" />
                            <textarea id="description" name="description" class="textarea mb-5">{{ $service->description }}</textarea>
                        </div>

                        <div>
                            <select id="category_id"  name ="category_id"
                            class="shadow-sm border-0 focus:outline-none p-3 block sm:text-sm border-gray-300 rounded-md mb-2">
                            <option value="">Sélectionnez un type de catégorie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if($category->id === $service->category_id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <h1 class="text-white text-2xl font-bold">Ajouter des paramètres aux service</h1>
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
                        <livewire:dynamic-input />


                        <h1 class="text-white text-2xl font-bold">Ajouter des documents requis pour l'inscription au service</h1>

                        <livewire:dynamic-select />

                        <div class="flex items-center gap-4 mt-5">
                            <button class="btn btn-active btn-neutral">Valider</button>
                        </div>
                    </form>
                </div>


                @if($service->parameters)
                    <h1 class="text-white text-2xl font-bold mt-3">{{_('Paramètres configurés')}}</h1>
                    @foreach ($service->parameters as $parameter)
                    <form action="{{ route('services.updateParameter', ['service' => $service, 'id' => $parameter->id ]) }}" method="POST">
                        @csrf
                        @method('patch')
                        <div class="mr-4 mt-5">
                            <div class="w-full flex items-center gap-4">
                                <input type="text" id="input_{{ $parameter->id }}_name"
                                    name="input_{{ $parameter->id }}_name"
                                    wire:model="inputs.{{ $parameter->id }}.name"
                                    class="shadow-sm border-0 focus:outline-none p-3 block w-full sm:text-sm border-gray-300 rounded-md"
                                    autocomplete="off" value="{{$parameter->name}}">
                                    @error('inputs_' . $parameter->id . '_name')
                                    <span class="text-xs text-red-600">{{ $message }}</span>
                                @enderror
                                <select id="input_{{ $parameter->id }}_type" wire:model="inputs.{{ $parameter->id }}.type" name="input_{{$parameter->id}}_type"
                                    class="shadow-sm border-0 focus:outline-none p-3 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="" disabled>Sélectionnez un type de donnée</option>
                                    @foreach (\App\Models\DataType::All() as $type)
                                        <option value="{{ $type->id }}" @if($type->id === $parameter->data_type_id) selected @endif>{{ $type->name }}</option>
                                    @endforeach
                                </select>

                                    <button type="submit" class="flex items-center justify-end text-green-600 text-sm cursor-pointer ml-4">
                                        <p>Modifer le paramètre</p>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('services.destroyParameter', ['service' => $service, 'id' => $parameter->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-end text-red-600 text-sm cursor-pointer ml-4">
                                        <p>Supprimer le paramètre</p>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if($service->documents)
                    <h1 class="text-white text-2xl font-bold mt-3">{{_('Documents requis')}}</h1>
                    @foreach ($service->documents as $document)
                    <div class="mt-5 mr-5">
                        <form action="{{ route('services.updateDocument', ['service' => $service, 'id' => $document->id ]) }}" method="POST">
                            @csrf
                            @method('patch')
                        <div class="w-full flex">
                                <div>
                                    <select id="documents"  name ="new_document_id"
                                        class="shadow-sm border-0 focus:outline-none p-3 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="" disabled>Sélectionnez un type de donnée</option>
                                        @foreach (\App\Models\Document::All() as $documentType)
                                            <option value="{{ $documentType->id }}" @if($documentType->id === $document->id) selected @endif>{{ $documentType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <button type="submit" class="flex items-center justify-end text-green-600 text-sm cursor-pointer ml-4">
                                    <p>Modifer le paramètre</p>
                                </button>
                            </form>

                            
                            <div class="flex items-center justify-end text-red-600 text-sm cursor-pointer ml-4">
                                <form method="POST" action="{{ route('services.destroyDocument', ['service' => $service, 'id' => $document->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-end text-red-600 text-sm cursor-pointer ml-4">
                                        <p>Supprimer le document</p>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
<script>
    const priceInput = document.getElementById('price');
    const flexPriceCheckbox = document.getElementById('flexPrice');
    
    if (flexPriceCheckbox.value == 1) {
        flexPriceCheckbox.checked = true;
        priceInput.disabled = true;
    }
    
    flexPriceCheckbox.addEventListener('change', function() {
        if (this.checked) {
            priceInput.disabled = true;
            priceInput.value = '';
        } else {
            priceInput.disabled = false;
        }
    });
</script>