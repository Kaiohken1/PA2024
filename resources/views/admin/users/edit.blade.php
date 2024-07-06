<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier les informations de l\'utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="roles" :value="__('Modifier les roles')" />
                            <select class="form-multiselect border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" multiple name="roles_id[]" id="roles_id">
                                @foreach ($roles as $roles)
                                    <option value="{{ $roles->id }}">{{ $roles->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <x-primary-button class="ms-3 mt-5 ml-0">
                            {{ __('Modifier') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
