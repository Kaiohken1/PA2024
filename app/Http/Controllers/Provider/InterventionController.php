<?php

namespace App\Http\Controllers\Provider;

use App\Models\Service;
use App\Models\Provider;
use App\Models\Appartement;
use App\Models\Intervention;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interventions = Intervention::query()
            ->select('id', 'description', 'statut', 'appartement_id', 'provider_id', 'service_id')
            ->latest()
            ->paginate(10);

        return view('interventions.index', [
            'interventions' => $interventions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $appartement_id = $request->route('id');
        $selectedAppartement = Appartement::find($appartement_id);
        $appartements = Appartement::all();

        $services = Service::All();

        return view('interventions.create', [
            'selectedAppartement' => $selectedAppartement,
            'services' => $services,
            'appartements' => $appartements
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'appartement_id' => ['required', 'exists:appartements,id'],
            'service_id' => ['required', 'exists:services,id'],
        ]);

        $user = Auth::user();
        $validatedData['user_id'] = Auth()->id();

        $service = Service::findOrfail($validatedData['service_id']);

        $validatedData['price'] = $service->price;

        $role = $user->roles->first()->nom;
    
        if ($role) {
            $validatedData['user_type'] = $role;
        }

        $intervention = new Intervention($validatedData);
        $intervention->user()->associate($validatedData['user_id']);
        $intervention->save();

        return redirect()->route('interventions.create', ['id' => $validatedData['appartement_id']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Intervention $intervention)
    {
        return view('interventions.show', compact('intervention'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Intervention $intervention)
    {
        return view('interventions.edit', compact('intervention'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Intervention $intervention)
    {
        $validatedData = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'appartement_id' => ['required', 'exists:appartements,id'],
            'reservation_id' => ['exists:reservations,id'],
            'user_id' => ['exists:users,id'],
            'service_id' => ['required', 'exists:services,id'],
            'price' => ['required', 'numeric'],
        ]);

        $intervention->update($validatedData);

        return redirect()->route('interventions.index')
            ->with('success', 'Intervention updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intervention $intervention)
    {
        $intervention->delete();

        return redirect()->route('interventions.index')
            ->with('success', 'Intervention deleted successfully.');
    }
}
