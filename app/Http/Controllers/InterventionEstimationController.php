<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommissionTier;
use App\Rules\CheckPlannedDate;
use Illuminate\Validation\Rule;
use App\Rules\CheckEventConflict;
use App\Models\InterventionEstimation;
use Illuminate\Support\Facades\Storage;

class InterventionEstimationController extends Controller
{
    public static function calculateCommission($price)
    {
        $tier = CommissionTier::where('min_amount', '<=', $price)
            ->where(function ($query) use ($price) {
                $query->where('max_amount', '>=', $price)
                    ->orWhereNull('max_amount');
            })
            ->first();

        return $price * ($tier->percentage / 100);
    }

    
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
            'estimate' => ['required', 'mimes:jpeg,jpg,png,pdf', 'max:5000'],
            'end_time' => [
                'required',
                'date',
                'after:planned_date',
                new CheckPlannedDate($request->intervention_id),
                new CheckEventConflict($request->provider_id, $request->end_time)    
            ],
            'price' => ['required', 'numeric'],
        ]);

        $doc = $request->file('estimate');
        $path = $doc->store('InterventionEstimates', 'public');
        $validatedData['estimate'] = $path;

        $validatedData['statut_id'] = 1;

        $validatedData['end_time'] = date("Y-m-d H:m:s", strtotime($validatedData['end_time']));


        $validatedData['commission'] = $this->calculateCommission($validatedData['price']);

        InterventionEstimation::create($validatedData);


        return back()->with('success','Devis envoyé au gestionnaire');
    }


    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'estimate' => ['required', 'mimes:jpeg,jpg,png,pdf', 'max:5000'],
            'end_time' => ['nullable',
            new CheckEventConflict($request->provider_id, $request->end_time)    
            ],
            'price' => ['nullable', 'numeric'],
        ]);

        $estimation = InterventionEstimation::findOrFail($id);

        Storage::disk('public')->delete($estimation->estimate);

        $doc = $request->file('estimate');
        $path = $doc->store('InterventionEstimates', 'public');
        $validatedData['estimate'] = $path;
        $estimation->statut_id = 1;
        $validatedData['end_time'] = date("Y-m-d H:m:s", strtotime($validatedData['end_time']));


        $validatedData['commission'] = $this->calculateCommission($validatedData['price']);

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
