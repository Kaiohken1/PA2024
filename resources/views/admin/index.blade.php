<x-admin-layout>
    <div class="py-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{ route('admin.users.index') }}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-ri-user-settings-fill class="h-12 w-12 text-white"/>
                    <p class="block text-center text-white py-2">Gestion des Utilisateurs</p>
                </div>
            </a>
        </div>

        <div class=" bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{ route('admin.property.index') }}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-heroicon-s-home class="h-12 w-12 text-white"/>
                    <p class="block text-center text-white py-2">Gestion des Appartements</p>
                </div>
            </a>
        </div>

        <div class=" bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{route('admin.reservations.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-heroicon-c-calendar-days class="h-12 w-12 text-white"/>
                    <p class="block text-center text-white py-2">Gestion des Réservations</p>
                </div>
            </a>
        </div>

        <div class=" bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{route('admin.providers.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-mdi-account-wrench class="h-16 w-16 text-white"/>
                    <p class="block text-center text-white py-2">Gestion des Prestataires</p>
                </div>
            </a>
        </div>

        <div class=" bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{route('services.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-heroicon-c-wrench-screwdriver class="h-12 w-12 text-white"/>
                    <p class="block text-center text-white py-2">Gestion des Services</p>
                </div>
            </a>
        </div>
        <div class=" bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{route('admin.interventions.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-fas-handshake class="h-12 w-12 text-white mr-2"/>
                    <p class="block text-center text-white py-2">Gestion des Interventions</p>
                </div>
            </a>
        </div>

        <div class=" bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{ route('tags.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-heroicon-s-tag class="h-12 w-12 text-white"/>
                    <p class="block text-center text-white py-2">Gestion des Tags</p>
                </div>
            </a>
        </div>

        <div class=" bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{route('documents.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-fas-file-alt class="h-12 w-12 text-white"/>
                    <p class="block text-center text-white py-2">Gestion des types de documents</p>
                </div>
            </a>
        </div>

        <div class=" bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{route('admin.invoices.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-fas-file-invoice-dollar class="h-12 w-12 text-white"/>
                    <p class="block text-center text-white py-2">Gestion Comptable</p>
                </div>
            </a>
        </div>

        <div class=" bg-grey-100 border overflow-hidden shadow-sm sm:rounded-lg">
            <a href="{{route('admin.commissions.index')}}" class="block hover:bg-gray-950">
                <div class="flex justify-center items-center h-40 pr-2 pl-2">
                <x-fas-hand-holding-dollar class="h-12 w-12 text-white"/>
                    <p class="block text-center text-white py-2">Gestion Comissions</p>
                </div>
            </a>
        </div>

        
    </div>
</x-admin-layout>
