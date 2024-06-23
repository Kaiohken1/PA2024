<div>
    <!-- Afficher les images principales -->
    <h2>Images principales ({{ $mainImages->count() }}/4)</h2>
    <div class="flex space-x-8 mb-4">
        @foreach ($mainImages as $image)
            <div class="relative">
                <img class="rounded-md mb-3 h-52" src="{{ Storage::url($image->image) }}" width="200px">
                <button wire:click="unsetMain({{ $image->id }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M6 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zM5.293 9.293a1 1 0 011.414 0L10 13.586l3.293-3.293a1 1 0 111.414 1.414L11.414 15l3.293 3.293a1 1 0 01-1.414 1.414L10 16.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 15 5.293 11.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endforeach
    </div>

    <h2>Autres images</h2>
    <div class="grid grid-cols-4 gap-4">
        @foreach ($otherImages as $image)
            <div class="relative">
                <img class="rounded-md mb-3 h-52" src="{{ Storage::url($image->image) }}" width="200px">
                <button wire:click="setMain({{ $image->id }})" class="absolute top-2 right-2 text-green-500 hover:text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <form method="POST" action="{{ route('property.destroyImg', $image) }}" class="absolute bottom-2 right-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M6 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zM5.293 9.293a1 1 0 011.414 0L10 13.586l3.293-3.293a1 1 0 111.414 1.414L11.414 15l3.293 3.293a1 1 0 01-1.414 1.414L10 16.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 15 5.293 11.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    @if (session()->has('error'))
        <div class="text-red-500 mt-4">{{ session('error') }}</div>
    @endif
</div>
