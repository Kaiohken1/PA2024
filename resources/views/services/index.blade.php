<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des services') }}
        </h2>

        @if (session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600" role="alert">
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
                            <th scope="col" class="px-6 py-3 text-center">Nom</th>
                            <th scope="col" class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $service->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <form method="POST" action="{{ route('services.destroy', $service) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
