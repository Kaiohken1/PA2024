<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="subject" :value="__('Objet de la demande')" />
                            <x-text-input id="subject" class="form-input block mt-1 w-full" type="text" name="subject" />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Catégorie')" />
                            <select class="select select-bordered w-full max-w-xs chosen-select border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" name="attributed_role_id" id=attributed_role_id>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->nom}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description (essayez d\'être le plus précis sur le problème rencontré)')"/>
                            <textarea id="description" class="form-input block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" name="description"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <x-primary-button class="ms-3 mt-5 ml-0">
                            {{ __('Créer un ticket') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>