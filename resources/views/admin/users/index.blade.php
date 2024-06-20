<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
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
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-sm text-left text-white">
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
                    <tbody class="bg-gray-800">
                        @foreach ($users as $user)
                            <tr class="border-b">
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
                                
                                <td class="py-3 px-6 text-center">
                                    <div class="flex justify-center space-x-2">        
                                        <button class="btn btn-outline bg-white btn-sm">
                                            <a href="{{ route('admin.users.edit', $user) }}">Modifier</a>
                                        </button>

                                        @if(!$user->isAdmin())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-outline bg-white btn-sm">Supprimer</button>
                                        </form>
                                        @endif
                                    </div>
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
</x-admin-layout>

