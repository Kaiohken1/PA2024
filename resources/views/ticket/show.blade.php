<x-app-layout>
    <x-slot name="header">
            <h2 class="font-semibold text-xl leading-tight">
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
                    <h2 class="text-2xl font-bold mb-4">{{ __('Ticket numéro') }} {{ $ticket->id }}</h2>
                    <p><strong>{{ __('Objet:') }}</strong> {{ $ticket->subject }}</p>
                    <p><strong>{{ __('Catégorie:') }}</strong> {{ $ticket->attributedRole->nom }}</p>
                    <p><strong>{{ __('Description:') }}</strong> {{ $ticket->description }}</p>
                    <p><strong>{{ __('Créé le:') }}</strong> {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i') }}</p>
                    


                    <h2 class="text-2xl font-bold mt-4 mb-2">{{ __('Réponse de PCS') }}</h2>
                    <p><strong>{{ __('Solution:') }}</strong> {{ $ticket->solution }}</p>
                    <p><strong>{{ __('Edité le:') }}</strong> {{ \Carbon\Carbon::parse($ticket->updated_at)->format('d/m/Y H:i') }}</p>
                    <p><strong>{{ __('Statut:') }}</strong> {{ $ticket->status }} </p>
                    @if ( $ticket->attributed_user_id !== NULL)
                    <button class="btn btn-outline bg-white btn-sm">
                        <a href="{{ route('tickets.chat', ['ticket' => $ticket->id, 'user' => $ticket->attributed_user_id]) }}">{{ __('Chat') }}</a>
                    </button>
                    @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
