<?php

namespace App\Http\Controllers\Provider;

use App\Events\InterventionPaid;
use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\Absence;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Appartement;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use App\Models\ServiceParameter;
use App\Models\InterventionEvent;
use App\Models\InterventionRefusal;
use App\Http\Controllers\Controller;
use App\Models\InterventionEstimate;
use Illuminate\Support\Facades\Auth;
use App\Models\InterventionEstimation;
use App\Models\Reservation;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interventions = Intervention::query()
                        ->where('user_id', Auth::user()->id)
                        ->paginate(10);

        return view('interventions.index', ['interventions' => $interventions]);
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

        return view('interventions.create', [
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
        ]);


        foreach ($validatedData['services'] as $service_id) {
            $intervention = Intervention::where('appartement_id', $validatedData['appartement_id'])
                ->where('service_id', $service_id)
                ->whereDate('planned_date', '=', date('Y-m-d', strtotime($validatedData['planned_date'])))
                ->where('statut_id', '!=', 4)
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
        return redirect()->route('interventions.dashboard')->with('success', 'Intervention en attente de validation');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $intervention = Intervention::findOrfail($id);

        $provider = Provider::findOrFail(Auth::user()->provider->id);
        $absences = Absence::query()
                    ->where('provider_id', $provider->id)
                    ->get();
    
        $datesInBase = [];
    
        foreach ($absences as $absence) {
            $datesInBase[] = [
                'from' => date("d-m-Y", strtotime($absence->start)),
                'to' => date("d-m-Y", strtotime($absence->end))
            ];
        }

        return view('interventions.show', ['intervention' => $intervention, 'datesInBase' => $datesInBase]);
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
    public function destroy($id, $intervention)
    {
        $intervention = Intervention::findOrFail($intervention);
        $intervention->statut_id = 4;
        $intervention->save();

        $intervention->delete();

        return redirect()->route('interventions.dashboard')
            ->with('success', 'Intervention annulée.');
    }


    public function attribuate(Intervention $intervention, Provider $provider) {
        $intervention->update(['provider_id'], $provider->id);
    }


    public function clientShow($id)
    {
        $intervention = Intervention::findOrfail($id);
        return view('interventions.client-show', ['intervention' => $intervention]);
    }

    public function showPaymentPage($id)
    {
        $intervention = Intervention::findOrFail($id);
        return view('payment.page', compact('intervention'));
    }


    public function checkout($id)
    {
        $intervention = Intervention::findOrFail($id);

        $taxRate = 0.20;

        $taxAmount = $intervention->price * $taxRate;
        $totalAmount = $intervention->price + $taxAmount;

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Intervention #' . $intervention->id,
                    ],
                    'unit_amount' => $totalAmount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('interventions.redirect', ['id' => $intervention->id, 'token' => uniqid()]),
            'cancel_url' => url('/'),
        ]);

        return redirect()->away($session->url);
    }

    public function redirect(Request $request, $id, $token)
    {
        $intervention = Intervention::findOrFail($id);
        return view('interventions.redirect', ['intervention' => $intervention, 'token' => $token]);
    }


    public function plan(Request $request, $id) {

        $intervention = Intervention::findOrFail($id);
        if($intervention->intervention_event !== NULL) {
            return view('interventions.client-show', ['intervention' => $intervention]);
        }
        $intervention->statut_id = 5;

        $intervention->save();

        $event = new InterventionEvent();
        $event->intervention_id = $intervention->id;
        $event->appartement_id = $intervention->appartement->id;
        $event->provider_id = $intervention->provider->id;
        $event->title = $intervention->service->name . " - " . $intervention->appartement->address;
        $event->start = $intervention->planned_date;
        $event->end = $intervention->planned_end_date;

        $event->save();

        event(new InterventionPaid($intervention));

        $estimation = InterventionEstimation::findOrFail($intervention->estimations->where('statut_id', 9)->first()->id);
        return redirect()->route('interventions.clientShow', ['id' => $intervention->id]);
    }


    public function showProvider($id)
    {
        $intervention = Intervention::findOrfail($id);
        return view('interventions.show-provider', ['intervention' => $intervention]);
    }

    public function refusal($id, Request $request) {
        $validatedData = $request->validate([
            'refusal' => ['required', 'string', 'max:255'],
        ]);

        $estimation = InterventionEstimation::findOrFail($id);
        $estimation->statut_id = 8;
        $estimation->save();

        $intervention = Intervention::findOrFail($estimation->intervention_id);

        InterventionRefusal::create([
            'intervention_id' => $intervention->id,
            'provider_id' => $intervention->provider_id,
            'statut_id' => 8,
            'refusal_reason' => $validatedData['refusal'],
            'estimate' => $estimation->estimate,
            'price' => $intervention->price,
            'planned_date' => $intervention->planned_date,
            'planned_end_date' => $intervention->planned_end_date,
        ]);

        $intervention->provider_id = NULL;
        $intervention->price = NULL;
        $intervention->commission = NULL;
        $intervention->statut_id = 1;
        
        $intervention->save();

        return redirect()->back()->with('success', 'Intervention refusée, un autre devis vous sera envoyé.');

    }
}

