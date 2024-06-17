<x-app-layout>
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
                                Catégorie
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Objet
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800">
                        @foreach ($tickets as $ticket)
                            <tr class="border-b">
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                    {{ $ticket->ticketCategory->category }}
                                </td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                    {{ $ticket->subject }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}
                                </td>

                                <td class="px-6 py-4 text-center">

                                </td>
    
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</x-app-layout>

