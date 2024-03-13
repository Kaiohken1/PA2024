<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appartement;

class AppartementController extends Controller
{
    public function display()
    {
        $appartements = Appartement::paginate(15);
        return view('appartement.display', compact('appartements'));
    }

    public function create()
    {
        $appartement = new Appartement();
        $appartement->titre = 'test titre';
        $appartement->prix = '10';
        $appartement->superficie = '15';
        $appartement->voyageurs = '20';
        $appartement->save();

        return $appartement;
    }
}

