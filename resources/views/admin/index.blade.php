<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Admin') }}
        </h2>
    </x-slot>

    
    <div>
        <h2>Notifications</h2>
        <ul>
            @foreach($notifications as $notification)
                <li>{{ $notification->data['message'] }}</li>
            @endforeach
        </ul>
    </div>


    <div class="py-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        <div class="max-w-xs mx-auto bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{ route('admin.users.index') }}" class="block hover:bg-gray-200">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                    <x-users-logo class="h-24 w-24"/>
                    <p class="block text-center py-2">Gestion des Utilisateurs</p>
                </div>
            </a>
        </div>

        <div class="max-w-xs mx-auto bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <a href="/" class="block hover:bg-gray-200">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                    <x-home-notification-logo class="h-24 w-24"/>
                    <p class="block text-center py-2">Gestion des Appartements</p>
                </div>
            </a>
        </div>

        <div class="max-w-xs mx-auto bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <a href="/" class="block hover:bg-gray-200">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                    <x-presta-logo class="h-24 w-24"/>
                    <p class="block text-center py-2">Gestion des Prestataires</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
