<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl  leading-tight text-white">
            {{ __('Editer le service')  }} {{$service->name}}
        </h2>
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

                        <div>
                            <x-input-label for="description" :value="__('Description du service')" class="text-white" />
                            <textarea id="description" name="description" class="textarea mb-5">{{ $service->description }}</textarea>
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
                                    wire:model.defer="inputs.{{ $parameter->id }}.name"
                                    class="shadow-sm border-0 focus:outline-none p-3 block w-full sm:text-sm border-gray-300 rounded-md"
                                    autocomplete="off" value="{{$parameter->name}}">
                                    @error('inputs_' . $parameter->id . '_name')
                                    <span class="text-xs text-red-600">{{ $message }}</span>
                                @enderror
                                <select id="input_{{ $parameter->id }}_type" wire:model.defer="inputs.{{ $parameter->id }}.type" name="input_{{$parameter->id}}_type"
                                    class="shadow-sm border-0 focus:outline-none p-3 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="" disabled>Sélectionnez un type de donnée</option>
                                    @foreach (\App\Models\DataType::All() as $type)
                                        <option value="{{ $type->id }}" @if($type->id === $parameter->data_type_id) selected @endif>{{ $type->name }}</option>
                                    @endforeach
                                </select>

                                    @csrf
                                    @method('patch')
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
            </div>
        </div>
    </div>

</x-app-layout>