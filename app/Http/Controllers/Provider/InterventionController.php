<?php

namespace App\Http\Controllers\Provider;

use App\Models\Service;
use App\Models\Provider;
use App\Models\Appartement;
use App\Models\Intervention;
use Illuminate\Http\Request;
use App\Models\ServiceParameter;
use App\Http\Controllers\Controller;
use App\Models\InterventionEstimate;
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
        $selectedServices = [];

        $services = Service::All()->where('active_flag', 1);

        return view('interventions.create', [
            'selectedAppartement' => $selectedAppartement,
            'services' => $services,
            'appartements' => $appartements,
            'selectedServices' => $selectedServices,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'appartement_id' => ['required', 'exists:appartements,id'],
            'text' => ['nullable', 'array'],
            'text.*' => ['array'],
            'text.*.*' => ['string', 'max:255'],
            'address' => ['nullable', 'array'],
            'address.*' => ['array'],
            'address.*.*' => ['string', 'max:255'],
            'surface' => ['nullable', 'array'],
            'surface.*' => ['array'],
            'surface.*.*' => ['numeric'],
            'roomCount' => ['nullable', 'array'],
            'roomCount.*' => ['array'],
            'roomCount.*.*' => ['numeric'],
            'number' => ['nullable', 'array'],
            'number.*' => ['array'],
            'number.*.*' => ['numeric'],
            'email' => ['nullable', 'array'],
            'email.*' => ['array'],
            'email.*.*' => ['email', 'max:255'],
            'tel' => ['nullable', 'array'],
            'tel.*' => ['array'],
            'tel.*.*' => ['regex:/[0-9]{10}/'],
            'description' => ['nullable', 'array'],
            'description.*' =>['nullable', 'string'],
            'date' => ['nullable', 'array'],
            'date.*' => ['array'],
            'date.*.*' => ['date'],
            'checkbox' => ['nullable', 'array'],
            'checkbox.*' => ['array'],
            'services' => ['required', 'array'],
            'services.*' => ['exists:services,id'],
            'planned_date' => ['required', 'date']
        ]);

        $user = Auth::user();
        $validatedData['user_id'] = Auth()->id();


        foreach ($validatedData['services'] as $id) {
            $service = Service::findOrfail($id);
            $price = $service->flexPrice = 1 ? null : $service->price;
            $validatedData['price'] = $price;
            $role = $user->roles->first()->nom;
            foreach($validatedData['description'] as $key => $value) {
                if($key == $id) {
                    $description = $value;
                }
            }

            if ($role) {
                $validatedData['user_type'] = $role;
            }

            $intervention = new Intervention($validatedData);
            $intervention->user()->associate($validatedData['user_id']);
            $intervention->service()->associate($id);
            $intervention->statut_id = 1;
            $intervention->description = $description;
            $intervention->service_version = $service->currentVersion()->version_id;
            $intervention->save();

            foreach ($validatedData as $value) {
                if (is_array($value) && array_key_exists($id, $value) && is_array($value[$id])) {
                    $parameters = $value[$id];
                    foreach ($parameters as $key => $content) {
                        $parameter = ServiceParameter::findOrfail($key);
                        $intervention->service_parameters()->attach($key, [
                            'value' => $content,
                            'intervention_id' => $intervention->id,
                            'service_id' => $id,
                            'parameter_version' => $parameter->currentVersion()->version_id,
                        ]);
                    }
                }
            }
        }
        return redirect()->route('property.index')->with('success', 'Intervention en attente de validation');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $intervention = Intervention::findOrfail($id);
        return view('interventions.show', ['intervention' => $intervention]);
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


    public function attribuate(Intervention $intervention, Provider $provider) {
        $intervention->update(['provider_id'], $provider->id);
    }

}
