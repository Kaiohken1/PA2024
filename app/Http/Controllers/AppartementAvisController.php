<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Appartement;
use Illuminate\Http\Request;
use App\Models\AppartementAvis;

class AppartementAvisController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $reservation_id = $request->id;
        
        $reservation = Reservation::findOrFail($reservation_id);
        
        
        return view('appartements.appartement_avis.create', [
            'reservation' => $reservation
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'comment' => ['required', 'max:255'],
            'appartement_id' => ['required', 'numeric'],
            'reservation_id' => ['required', 'numeric'],
            'rating_cleanness' => ['required', 'numeric', 'max:5'],
            'rating_price_quality' => ['required', 'numeric', 'max:5'],
            'rating_location' => ['required', 'numeric', 'max:5'],
            'rating_communication' => ['required', 'numeric', 'max:5'],
        ]);
        
        $validateData['user_id'] = Auth()->id();
    
        $AppartementAvis = new AppartementAvis();
        $AppartementAvis->comment = $validateData['comment'];
        $AppartementAvis->appartement_id = $validateData['appartement_id'];
        $AppartementAvis->reservation_id = $validateData['reservation_id'];
        $AppartementAvis->rating_cleanness = $validateData['rating_cleanness'];
        $AppartementAvis->rating_price_quality = $validateData['rating_price_quality'];
        $AppartementAvis->rating_location = $validateData['rating_location'];
        $AppartementAvis->rating_communication = $validateData['rating_communication'];
        $AppartementAvis->user()->associate($validateData['user_id']);
        $AppartementAvis->save();

        return redirect()->route('property.show', $validateData['appartement_id'])
            ->with('success', "Avis envoyé avec succès");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $appartement_id, string $avis_id)
    {
        $avis = AppartementAvis::findOrfail($avis_id);

        return view('appartements.appartement_avis.edit',[
            'avis' => $avis
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $appartement_id, string $avis_id)
    {
        $avis = AppartementAvis::findOrFail($avis_id);

        $validateData = $request->validate([
            'comment' => ['required', 'max:255'],
            'appartement_id' => ['required', 'numeric'],
            'reservation_id' => ['required', 'numeric'],
            'rating_cleanness' => ['required', 'numeric', 'max:5'],
            'rating_price_quality' => ['required', 'numeric', 'max:5'],
            'rating_location' => ['required', 'numeric', 'max:5'],
            'rating_communication' => ['required', 'numeric', 'max:5'],
        ]);
        
    

        $avis->update($validateData);

        return redirect()->route('property.show', $appartement_id)
            ->with('success', "Tag mis à jour avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $appartement_id, string $avis_id)
    {

        $avis = AppartementAvis::findOrFail($avis_id);

        $avis->delete();
        
        return redirect()->route('property.show', $appartement_id)
            ->with('success', "Avis supprimé avec succès");
    
    }
}
