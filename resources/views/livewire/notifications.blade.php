<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative">
        <svg class="w-6 h-10 mt-3 mr-3 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405C18.79 14.79 18 13.42 18 12V5a7 7 0 00-14 0v7c0 1.42-.79 2.79-2.595 3.595L2 17h5m4 0v1a3 3 0 006 0v-1m-6 0H9"/>
        </svg>
        @if($notifications->count() > 0)
            <span class="absolute top-0 right-0 block h-2 w-2 mt-4 mr-4 transform translate-x-1/2 -translate-y-1/2 rounded-full bg-red-600 ring-2 ring-white"></span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-96 bg-white rounded-md shadow-lg overflow-hidden z-20" style="display: none;">
        <div class="py-2">
            @forelse($notifications as $notification)
                <div class="px-4 py-2 border-b">
                    <p class="text-sm text-gray-600">{{ $notification->data['message'] }}</p>
                    <button wire:click="markAsRead('{{ $notification->id }}')" class="text-blue-500 text-sm">Marquer comme lue</button>
                </div>
            @empty
                <p class="px-4 py-2 text-gray-600">Pas de nouvelles notifications</p>
            @endforelse
        </div>
    </div>
</div>
