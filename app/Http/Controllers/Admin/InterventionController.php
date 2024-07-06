<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\Provider;
use App\Models\Appartement;
use App\Models\Reservation;
use App\Models\Intervention;
use Illuminate\Http\Request;
use App\Events\InterventionPaid;
use App\Models\ServiceParameter;
use App\Models\InterventionEvent;
use App\Models\InterventionRefusal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Events\InterventionDevisSend;
use App\Models\InterventionEstimation;

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
    public function create(Request $request)
    {
        $appartement_id = $request->route('id');
        $selectedAppartement = Appartement::withTrashed()->findOrFail($appartement_id);
        $reservation_id = $request->route('reservationId');
        $appartements = Appartement::all();
        $selectedServices = [];

        $reservation = null;

        if ($reservation_id) {
            $reservation = Reservation::find($reservation_id);
        }
        $user = Auth::user();
    
        $userRoles = $user->roles->pluck('id'); 
    
        $services = Service::whereIn('role_id', $userRoles)
                        ->where('active_flag', 1)
                        ->get();

        return view('admin.interventions.create', [
            'selectedAppartement' => $selectedAppartement,
            'services' => $services,
            'appartements' => $appartements,
            'selectedServices' => $selectedServices,
            'reservation' => $reservation,
            'start_time' => $reservation ? $reservation->start_time : null,
            'end_time' => $reservation ? $reservation->end_time : null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'appartement_id' => ['required', 'exists:appartements,id'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
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
            'information' =>['nullable', 'string'],
            'date' => ['nullable', 'array'],
            'date.*' => ['array'],
            'date.*.*' => ['date'],
            'checkbox' => ['nullable', 'array'],
            'checkbox.*' => ['array'],
            'services' => ['required', 'array'],
            'services.*' => ['exists:services,id'],
            'planned_date' => ['required', 'date', 'after:now'],
            'max_end_date' => ['nullable', 'date', 'after:planned_date'],
            'provider_id' => ['nullable', 'exists:providers,id'],
        ]);


        foreach ($validatedData['services'] as $service_id) {
            $intervention = Intervention::where('appartement_id', $validatedData['appartement_id'])
                ->where('service_id', $service_id)
                ->whereDate('planned_date', '=', date('Y-m-d', strtotime($validatedData['planned_date'])))
                ->exists();

            if ($intervention) {
                return redirect()->back()->withErrors(['error' => 'Il y a déjà une demande pour ce service à cette date.']);
            }
        }

        $user = Auth::user();
        $validatedData['user_id'] = Auth()->id();

        $validatedData['planned_date'] = date("Y-m-d H:m:s", strtotime($validatedData['planned_date']));

        if(isset($validatedData['max_end_date'])) {
            $validatedData['max_end_date'] = date("Y-m-d H:m:s", strtotime($validatedData['max_end_date']));

        }

        foreach ($validatedData['services'] as $id) {
            $service = Service::findOrfail($id);
            $price = $service->flexPrice = 1 ? null : $service->price;
            $validatedData['price'] = $price;
            $role = $user->roles->first()->nom;
            if(isset($validatedData['description'])) {
                foreach($validatedData['description'] as $key => $value) {
                    if($key == $id) {
                        $description = $value;
                    }
                }
            }

            if ($role) {
                $validatedData['user_type'] = $role;
            }

            $intervention = new Intervention($validatedData);
            $intervention->user()->associate($validatedData['user_id']);
            $intervention->service()->associate($id);
            $intervention->statut_id = 1;
            // $intervention->description = $description;
            $intervention->description = $validatedData['information'];
            $intervention->service_version = $service->currentVersion()->version_id;
            $intervention->provider_id = $validatedData['provider_id'];
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
        return redirect()->route('admin.property.index')->with('success', 'Intervention en attente de validation');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $intervention = Intervention::withTrashed()->findOrfail($id);
        $refusals = InterventionRefusal::query()
                    ->where('intervention_id', $intervention->id)
                    ->paginate(5);

        return view('admin.interventions.show', ['intervention' => $intervention, 'refusals' => $refusals]);
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
            'commission' => ['required', 'numeric'],
            'provider_id' => ['required', 'exists:providers,id'],
            'planned_end_date' => ['required'],
        ]);

        $intervention->statut_id = 10;

        $intervention->update($validatedData);

        $estimation = InterventionEstimation::findOrfail($intervention->estimations->where('provider_id', $validatedData['provider_id'])->first()->id);
        
        $estimation->statut_id = 9;

        $estimation->save();

        event(new InterventionDevisSend($intervention));

        return redirect()->route('admin.interventions.show', $intervention->id)
                ->with('succes', 'Proposition envoyée au client');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intervention $intervention)
    {
        //
    }

    public function plan(Request $request, $id) {

        $intervention = Intervention::findOrFail($id);

        $validatedData = $request->validate([
            'price' => ['required', 'numeric'],
            'commission' => ['required', 'numeric'],
            'provider_id' => ['required', 'exists:providers,id'],
            'planned_end_date' => ['required'],
        ]);

        $intervention->statut_id = 5;

        $intervention->update($validatedData);

        $estimation = InterventionEstimation::findOrfail($intervention->estimations->where('provider_id', $validatedData['provider_id'])->first()->id);
        
        $estimation->statut_id = 9;

        $estimation->save();

        event(new InterventionDevisSend($intervention));

        if($intervention->intervention_event !== NULL) {
            return redirect()->route('admin.interventions.show', $intervention->id)
                    ->with('error', 'Intervention déjà planifiée');
        }

        $event = new InterventionEvent();
        $event->intervention_id = $intervention->id;
        $event->appartement_id = $intervention->appartement->id;
        $event->provider_id = $intervention->provider->id;
        $event->title = $intervention->service->name . " - " . $intervention->appartement->address . $intervention->appartement->city;
        $event->start = $intervention->planned_date;
        $event->end = $intervention->planned_end_date;

        $event->save();

        event(new InterventionPaid($intervention));

        return redirect()->route('admin.interventions.show', $intervention->id)
        ->with('succes', 'Intervention planifiée');
    }
}
