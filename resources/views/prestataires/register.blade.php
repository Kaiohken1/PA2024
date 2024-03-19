<x-app-layout>
    <form method="POST" action="{{ route('prestataire') }}">
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

        <button type="submit">Enregistrer</button>
    </form>
</x-app-layout>