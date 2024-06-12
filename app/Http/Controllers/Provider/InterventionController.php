<?php

namespace App\Http\Controllers\Provider;

use Stripe\Stripe;
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
use App\Models\Invoice;

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
            'success_url' => route('interventions.plan', $intervention->id),
            'cancel_url' => url('/'),
        ]);

        return redirect()->away($session->url);
    }


    public function plan(Request $request, $id) {

        $intervention = Intervention::findOrFail($id);
        $intervention->statut_id = 5;

        $intervention->save();

        $event = new InterventionEvent();
        $event->intervention_id = $intervention->id;
        $event->provider_id = $intervention->provider->id;
        $event->title = $intervention->service->name;
        $event->start = $intervention->planned_date;
        $event->end = $intervention->planned_end_date;

        $event->save();

        $invoice = new Invoice();
        $invoice->intervention_id = $intervention->id;
        $invoice->provider_id = $intervention->provider->id;
        $invoice->user_id = $request->user()->id;
        $invoice->price = $intervention->price;

        $invoice->save();

        $estimation = InterventionEstimation::findOrFail($intervention->estimations->where('statut_id', 9)->first()->id);
        return view('interventions.client-show', ['intervention' => $intervention]);
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
