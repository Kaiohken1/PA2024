<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl  leading-tight text-white">
            {{ __('Editer le document') }} {{ $document->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-grey-800 border shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form action="{{ route('documents.update', $document) }}" method="post">
                        @csrf
                        @method('patch')
                        <div>
                            <x-input-label for="name" :value="__('Nom du document')" class="text-white" />
                            <x-text-input id="name" name="name" type="text"
                                class="input input-bordered w-full max-w-xs" :value="old('name', $document->name)" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>

                            <div>
                                <x-input-label for="description" :value="__('Description du document')" class="text-white" />
                                <textarea id="description" name="description" class="textarea mb-5">{{ $document->description }}</textarea>
                            </div>

                            <div class="flex items-center gap-4 mt-5">
                                <button class="btn btn-active btn-neutral">Valider</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
