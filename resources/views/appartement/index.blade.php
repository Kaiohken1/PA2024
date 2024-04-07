<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Liste des appartements') }}
            </h2>
            <x-nav-link :href="route('appartements.create')">
                {{ __('Créer un appartement') }}
            </x-nav-link>
        </div>
        

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-800">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                Titre
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Prix
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                superficie
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Voyageurs
                            </th>
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($appartements as $appartement)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                    {{ $appartement->titre }}
                                </td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                    {{ $appartement->prix }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $appartement->superficie }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $appartement->voyageurs }}
                                </td>
                                <td>
                                    <form action="{{ route('appartements.destroy', ['appartement' => $appartement]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button>🗑</button>
                                    </form>
                                </td>               
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
            {{ $appartements->links() }}
            </div>
        </div>
</x-app-layout>

