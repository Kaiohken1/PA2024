<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion des documents') }}
        </h2>

        <x-nav-link :href="route('documents.create')">
            {{ __('Ajouter un type de documents') }}
        </x-nav-link>

        @if (session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600" role="alert">
                {{ session('success') }}
            </div>
                @elseif (session('error'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600"
                role="alert">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-sm text-left text-white">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">Nom</th>
                            <th scope="col" class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                            <tr class="bg-gray-800 border-b">
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $document->name }}</td>
                                <td class="flex justify-center mt-3 mb-3">
                                    <a href="{{ route('documents.edit', $document) }}">
                                        <button class="btn btn-success mr-3">Editer</button>
                                    </a>
                                    <form method="POST" action="{{ route('documents.destroy', $document) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-error" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>