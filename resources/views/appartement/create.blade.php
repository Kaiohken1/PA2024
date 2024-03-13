@extends('base')

@section('title', 'Créer un appartement')

@section('content')
    <form action="" method="post"></form>
        <input type="text" name="titre" value="Titre de l'appartement">
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
        <input type="number" name="prix">
        <input type="number" name="superficie">
    </form>


@endsection