<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Création d\'appartement') }}
        </h2>

    </x-slot>
    <div class="w-1/2">
        <form class="flex flex-col content-center" action="{{ route('appartements.store') }}" method="POST">
            @csrf
            <label for="titre">Titre</label>
            <input type="text" name="titre">
            <label for="voyageurs">Voyageurs</label>
            <select name="voyageurs">
                <option value="" disabled selected>Nombre de voyageurs</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
            <label for="prix">Prix</label>
            <input type="number" name="prix">
            <label for="superficie">Superficie</label>
            <input type="number" name="superficie">
            <label for="adresse">Adresse</label>
            <input type="text" name="adresse">
            <button>Créer l'appartement</button>
        </form>
        </div>
</x-app-layout>

