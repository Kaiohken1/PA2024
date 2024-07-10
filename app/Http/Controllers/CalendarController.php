<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function show($appartement_id)
    {
        $appartement = Appartement::findOrFail($appartement_id);
        
        return view('home', compact('appartement'));
    }


    public function showAdmin($appartement_id)
    {
        $appartement = Appartement::findOrFail($appartement_id);
        
        return view('admin.property.calendar', compact('appartement'));
    }
}
