<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Catégories') }}
        </h2>

        <x-nav-link :href="route('ticket-categories.create')">
            {{ __('Créer une Catégorie') }}
        </x-nav-link>
    </x-slot>
    <div class="flex justify-center">
            @forelse ($ticketCategories as $ticketCategory)
                <div class="mt-9">
                    <p>{{ $ticketCategory->category }}</p>
                    <a href="{{ route('ticket-categories.edit', $ticketCategory) }}" class="mr-2">
                    <x-primary-button>Modifier</x-primary-button>
                    </a>

                    <form action="{{ route('ticket-categories.destroy', $ticketCategory) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-danger-button> Supprimer</x-danger-button>
                    </form>
                </div>
            @empty
                <p class="col-span-2">Il n'y a pas encore de catégorie </p>
            @endforelse
    </div>
</x-app-layout>