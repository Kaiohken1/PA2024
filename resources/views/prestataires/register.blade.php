<x-app-layout>
    <form method="POST" action="{{ route('prestataire') }}" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
        </div>

        <div>
            <label for="description">Description :</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <div>
            <label for="tarif">Tarif :</label>
            <input type="number" id="tarif" name="tarif" step="0.01" required>
        </div>

        <div>
            <x-input-label for="image" :value="__('image')" />
            <input type="file" name="image" id="image" class="file-input file-input-ghost w-full max-w-xs border-gray-300">
            @error('image')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
        </div>
        

        <button type="submit">Enregistrer</button>
    </form>
</x-app-layout>