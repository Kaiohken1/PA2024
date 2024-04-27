<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\Fermeture;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;


class FermetureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Appartement $appartement)
    {

        $appartement_id = $appartement->id;

        $intervalle = Reservation::where("appartement_id", $appartement_id)
            ->select("start_time","end_time")
            ->get();


        $fermetures = Fermeture::where('appartement_id', $appartement->id)->get();
        return view('fermetures.index', ['fermetures' => $fermetures,
                                                'appartement' => $appartement,
                                                'intervalles' => $intervalle]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($appartement)
    {

        $intervalle = Reservation::where("appartement_id", $appartement)
            ->select("start_time","end_time")
            ->get();

        $fermeture = Fermeture::where("appartement_id", $appartement)
            ->select("start_time","end_time")
            ->get();


        return view('fermetures.create', ['appartement'=>$appartement,
                                                'intervalles'=>$intervalle,
                                                'fermetures'=>$fermeture]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $appartement)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => ['required', 'date', 'after_or_equal:today'],
            'end_time' => ['required', 'date', 'after:start_time'],
        ]);

        $validatedData = $validator->validated();

        $fermeture = new Fermeture();
        $fermeture->start_time = $validatedData['start_time'];
        $fermeture->end_time = $validatedData['end_time'];
        $fermeture->appartement_id = $appartement;
        $fermeture->save();

        $appartement = Appartement::findOrFail($appartement);

        return redirect()->route('fermeture.index', ['appartement' => $appartement])->with('success', "Réservation bien prise en compte");
    }

    /**
     * Display the specified resource.
     */
    public function show(Fermeture $fermeture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fermeture $fermeture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $appartement, $fermeture)
    {

        $fermetures = Fermeture::findOrFail($fermeture);

        $validator = Validator::make($request->all(), [
            'start_time' => ['required', 'date', 'after_or_equal:today'],
            'end_time' => ['required', 'date', 'after:start_time'],
        ]);

        $validatedData = $validator->validated();

        $fermetures->start_time = $validatedData['start_time'];
        $fermetures->end_time = $validatedData['end_time'];
        $fermetures->save();

        return redirect()->route('fermeture.index', $appartement)
            ->with('success', 'Reservation deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($appartement, $fermeture)
    {
        $fermetures = Fermeture::findOrFail($fermeture);

        $fermetures->delete();

        return redirect()->route('fermeture.index', $appartement)
            ->with('success', 'Reservation deleted successfully');
    }
}
