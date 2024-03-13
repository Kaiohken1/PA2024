<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des appartements') }}
        </h2>

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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</x-app-layout>

