<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des utilisateurs') }}
        </h2>

        @if(session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-yellow-800 rounded-lg bg-yellow-50  dark:text-yellow-600" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </x-slot>

    <table class="table-auto">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf
                            @method('delete')
                            <x-dropdown-link :href="route('admin.users.destroy', $user)" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Supprimer') }}
                            </x-dropdown-link>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>

