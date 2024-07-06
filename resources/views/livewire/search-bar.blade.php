<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <input 
        type="text" 
        wire:model.live="search" 
        class="border rounded p-2 w-full" 
        placeholder="Rechercher un prestataire"
        @focus="open = true"
        @input="open = true"
    >
    @if(count($providers) > 0)        
        <ul 
            x-show="open" 
            class="absolute left-0 right-0 mt-2 bg-white border border-gray-300 rounded shadow-lg max-h-48 overflow-y-auto z-10"
            style="display: none;"
        >
            @foreach ($providers as $provider)
                <li 
                    wire:key="{{ $provider->id }}" 
                    class="p-2 hover:bg-gray-200 cursor-pointer"
                    @click="open = false"
                >
                    <a href="{{ route('admin.providers.show', $provider) }}">
                        <div class="flex flex-col">
                                <span>{{ $provider->name }}</span>
                                <small>{{ $provider->email }}</small>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
