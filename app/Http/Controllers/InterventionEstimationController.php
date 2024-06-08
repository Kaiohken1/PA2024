<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\InterventionEstimation;
use Illuminate\Support\Facades\Storage;

class InterventionEstimationController extends Controller
{
    public function store(Request $request) {

        $validatedData = $request->validate([
            'intervention_id' => ['required', 'exists:interventions,id'],
            'provider_id' => [
                'required', 
                'exists:providers,id', 
                Rule::unique('intervention_estimations')->where(function ($query) use ($request) {
                    return $query->where('intervention_id', $request->intervention_id);
                })
            ],
            'estimate' => ['required', 'image'],
            'end_time' => ['required'],
            'price' => ['required', 'numeric'],
        ]);

        $doc = $request->file('estimate');
        $path = $doc->store('InterventionEstimates', 'public');
        $validatedData['estimate'] = $path;

        $validatedData['statut_id'] = 1;

        InterventionEstimation::create($validatedData);


        return back()->with('success','Devis envoyé au gestionnaire');
    }


    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'estimate' => ['required', 'image'],
            'end_time' => ['nullable'],
            'price' => ['nullable', 'numeric'],
        ]);

        $estimation = InterventionEstimation::findOrFail($id);

        Storage::disk('public')->delete($estimation->estimate);

        $doc = $request->file('estimate');
        $path = $doc->store('InterventionEstimates', 'public');
        $validatedData['estimate'] = $path;
        $estimation->statut_id = 1;

        $estimation->update($validatedData);


        return redirect()->back()->with('success', 'Devis renvoyé avec succès.');
    }


    public function destroy($id)
    {
        $estimation = InterventionEstimation::findOrFail($id);

        Storage::disk('public')->delete($estimation->estimate);

        $estimation->delete();

        return redirect()->back()->with('success', 'Devis supprimé avec succès.');
    }
}
