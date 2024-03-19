<?php

namespace App\Http\Controllers\Presta;

use Illuminate\Http\Request;
use App\Events\PrestataireCreated;
use App\Models\Presta\Prestataire;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class PrestataireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('prestataires.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'tarif' => ['required', 'numeric', 'between:0,100']
        ]);

        $presta = Prestataire::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'tarif' => $request->tarif,
            'user_id' => Auth::user()->id,
        ]);
        
        event(new PrestataireCreated($presta));

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestataire $prestataire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestataire $prestataire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestataire $prestataire)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestataire $prestataire)
    {
        //
    }
}
