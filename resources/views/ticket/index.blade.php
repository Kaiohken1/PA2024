<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Mes demandes') }}
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
                <table class="w-full text-sm text-left">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                            {{ __('Objet') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                            {{ __('Catégorie') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                            {{ __('Date') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                            {{ __('Actions') }}
                            </th>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($tickets as $ticket)
                        <tr class="border-b">

                            <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                {{ $ticket->subject }}
                            </td>
                            <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                {{ $ticket->attributedRole->nom }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}
                            </td>

                            <td class="py-3 px-6 text-center">
                                <div class="flex justify-center space-x-2">
                                    <button class="btn btn-outline btn-sm">
                                        <a href="{{ route('tickets.show', ['ticket' => $ticket->id]) }}">{{ __('Détail') }}</a>
                                    </button>
                                    @if ($ticket->attributed_user_id == null)

                                    <button class="btn btn-outline btn-sm">
                                        <a href="{{ route('tickets.edit', ['ticket' => $ticket->id]) }}">{{ __('Modifier') }}</a>
                                    </button>

                                    <form action="{{ route('tickets.destroy', ['ticket' => $ticket->id]) }}" method="post" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" class="btn btn-outline btn-sm" value="Supprimer">
                                    </form>
                                    @endif
                                </div>
                            </td>



                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>