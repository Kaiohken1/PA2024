<x-admin-layout>
    <div class="py-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        <div class="max-w-xs mx-auto bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{ route('admin.users.index') }}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                    <x-users-logo class="h-24 w-24"/>
                    <p class="block text-center text-white py-2">Gestion des Utilisateurs</p>
                </div>
            </a>
        </div>

        <div class="max-w-xs mx-auto bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="/admin" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                    <x-home-notification-logo class="h-24 w-24"/>
                    <p class="block text-center text-white py-2">Gestion des Appartements</p>
                </div>
            </a>
        </div>

        <div class="max-w-xs mx-auto bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{route('providers.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                    <x-presta-logo class="h-24 w-24"/>
                    <p class="block text-center text-white py-2">Gestion des Prestataires</p>
                </div>
            </a>
        </div>

        <div class="max-w-xs mx-auto bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{route('services.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                    <x-presta-logo class="h-24 w-24"/>
                    <p class="block text-center text-white py-2">Gestion des Services</p>
                </div>
            </a>
        </div>
    </div>
</x-admin-layout>
