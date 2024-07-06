<x-app-layout>
    <x-slot name="header">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Détail du ticket') }}
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
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-4">Ticket numéro {{ $ticket->id }}</h2>
                    <p><strong>Objet:</strong> {{ $ticket->subject }}</p>
                    <p><strong>Catégorie:</strong> {{ $ticket->attributedRole->nom }}</p>
                    <p><strong>Description:</strong> {{ $ticket->description }}</p>
                    <p><strong>Créé le:</strong> {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}</p>
                    


                    <h2 class="text-2xl font-bold mt-4 mb-2">Réponse de PCS</h2>
                    <p><strong>Solution:</strong> {{ $ticket->solution }}</p>
                    <p><strong>Edité le:</strong> {{ \Carbon\Carbon::parse($ticket->updated_at)->format('d/m/Y H:i') }}</p>

                    
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
