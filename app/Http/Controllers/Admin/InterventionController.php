<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interventions = Intervention::All();
        
        $services = Service::All();

        return view('admin.interventions.index', [
            'interventions' => $interventions,
            'services' => $services,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $intervention = Intervention::findOrfail($id);
        return view('admin.interventions.show', ['intervention' => $intervention]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Intervention $intervention)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);

        $validatedData = $request->validate([
            'price' => ['required', 'numeric'],
            'provider_id' => ['required', 'exists:providers,id'],
            'planned_end_date' => ['required'],
        ]);

        $intervention->update($validatedData);

        return back()->with('succes', 'Proposition envoyée au client');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intervention $intervention)
    {
        //
    }
}
