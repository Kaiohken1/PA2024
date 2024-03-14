<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-center">
                    <x-users-logo/>
                    <a href="{{ route('admin.users.index') }}" class="mt-3 ml-2 hover:text-gray-500">Gestion des Utilisateurs</a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-7 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex align-middle justify-center">
                    <x-home-notification-logo/>
                    <a href="/" class="mt-3 ml-2 hover:text-gray-500">Gestion des Appartements</a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-7 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex align-middle justify-center">
                    <x-presta-logo/>
                    <a href="/" class="mt-3 ml-2 hover:text-gray-500">Gestion des Prestataires</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>