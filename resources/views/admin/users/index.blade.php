<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des utilisateurs') }}
        </h2>

        @if(session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-yellow-800 rounded-lg bg-yellow-50  dark:text-yellow-600" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-800">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                Nom
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Prénom
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Rôles
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($users as $user)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                    {{ $user->first_name }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 text-center capitalize">
                                    @foreach ($user->roles as $role)
                                        {{ $role->nom }}
                                        <br>
                                    @endforeach
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    @if(!$user->isAdmin())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">Non autorisé</span>
                                    @endcan
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
            {{ $users->links() }}
            </div>
        </div>
</x-app-layout>

