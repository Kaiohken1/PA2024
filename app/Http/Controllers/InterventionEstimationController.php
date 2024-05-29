<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterventionEstimation;

class InterventionEstimationController extends Controller
{
    public function store(Request $request) {

        $validatedData = $request->validate([
            'intervention_id' => ['required', 'exists:interventions,id'],
            'provider_id' => ['required', 'exists:providers,id', 'unique:intervention_estimations'],
            'estimate' => ['required', 'image'],
        ]);

        $doc = $request->file('estimate');
        // $path = $doc->store('InterventionEstimates', 'public');
        // $validatedData['estimate'] = $path;

        InterventionEstimation::create($validatedData);

        return back()->with('success','Devis envoyé au gestionnaire');
    }
}
