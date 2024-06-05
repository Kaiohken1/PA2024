<?php

namespace App\Http\Controllers\Provider;

use App\Models\Service;
use App\Models\Provider;
use App\Models\Intervention;
use Illuminate\Http\Request;
use App\Models\ProviderDocument;
use App\Notifications\NewProvider;
use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Support\Facades\Auth;
use App\Models\InterventionEstimation;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = Provider::query()
            ->select(['id', 'name', 'email', 'statut'])
            ->latest()
            ->paginate(10);

        return view('provider.index', [
            'providers' => $providers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::query()
            ->select(['id', 'name'])
            ->get();

        return view('provider.create', [
            'services' => $services
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required'],
            'address' => ['required', 'max:255'],
            'phone' => ['required', 'numeric'],
            'email' => ['required', 'unique:providers'],
            'description' => ['required', 'max:255'],
            'avatar' => ['image'],
            'service_id' => ['required', 'exists:services,id'],
            'provider_description' => ['max:255'],
            'documents' => ['required', 'array'],
            'documents.*' => ['mimes:jpg,png,pdf'],
            'bareme' => ['mimes:jpg,png,pdf']
        ]);

        $validateData['user_id'] = Auth()->id();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('providerPfp', 'public');
            $validateData['avatar'] = $path;
        }

        $provider = new Provider($validateData);
        $provider->user()->associate($validateData['user_id']);

        $provider->save();

        if ($request->hasFile('documents')) {
            $documents = $request->file('documents');

            foreach ($documents as $documentIndex => $file) {
                $path = $file->store('providersDocs', 'public');

                $provider->documents()->attach($documentIndex, [
                    'service_id' => $validateData['service_id'],
                    'provider_id' => $provider->id,
                    'document' => $path,
                ]);
            }
        }

        $validateData['bareme'] = $request->hasFile('bareme') ? $validateData['bareme']->store('providersDocs', 'public') : null;

        $provider->services()->attach($validateData['service_id'], [
            'price_scale' => $validateData['bareme'],
            'description' => $validateData['provider_description'],
        ]);

        return redirect('/')
            ->with('success', "Votre demande a bien été prise en compte, elle sera soumise à validation par un administrateur");
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        return view('provider.show', ['provider' => $provider, 'service' => $provider->services->first()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Provider $provider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {

        foreach ($provider->documents as $document) {
            Storage::disk('public')->delete($document->pivot->document);

        }

        if($provider->services->first()->pivot->price_scale) {
            Storage::disk('public')->delete($provider->services->first()->pivot->price_scale);
        }

        if ($provider->avatar !== NULL) {
            Storage::disk('public')->delete($provider->avatar);
        }

        $provider->delete();

        return redirect()->route('providers.index')
            ->with('success', 'Le prestataire a été supprimé avec succès');
    }

    public function validateProvider($id)
    {

        Provider::where('id', $id)->update(['statut' => 'Validé']);

        return redirect()->route('providers.index')
            ->with('success', 'Le prestataire a été validé avec succès');
    }


    public function home() {

        $provider = Provider::findOrFail(Auth::user()->provider->id);

        return view('provider.home', ['provider' => $provider]);
    }

    public function proposals() {
        $provider = Provider::findOrFail(Auth::user()->provider->id);
        $interventions = Intervention::query()
                            ->where('service_id', $provider->services->first()->id)
                            ->where('statut_id', 1)
                            ->latest()
                            ->paginate(15);

        return view('provider.proposals', ['interventions' => $interventions]);
    }

    public function calendar() {
        $provider = Provider::findOrFail(Auth::user()->provider->id);
        return view('provider.calandar', ['provider' => $provider]);
    }

    public function availability() {
        $provider = Provider::findOrFail(Auth::user()->provider->id);
        $absences = Absence::all(); 
    
        $datesInBase = [];
    
        foreach ($absences as $absence) {
            $datesInBase[] = [
                'from' => date("d-m-Y", strtotime($absence->start)),
                'to' => date("d-m-Y", strtotime($absence->end))
            ];
        }
    
        return view('provider.set-availability', ['provider' => $provider, 'datesInBase' => $datesInBase]);
    }

    public function availabilityCreate(Request $request) {
        $validatedData = $request->validate([
            'startDate' => ['required', 'date'], 
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
            'providerId' => ['required', 'exists:providers,id'],
        ]);
    
        $startDate = date("Y-m-d", strtotime($validatedData['startDate']));
        $endDate = date("Y-m-d", strtotime($validatedData['endDate']));
    
        $absence = new Absence();
        $absence->start = $startDate;
        $absence->end = $endDate;
        $absence->provider_id = $validatedData['providerId'];
        $absence->title = 'Absence';
        $absence->save();
    
        return back()->with('success', 'Période enregistrée avec succès');
    }

    public function availabilityDestroy($id) {

        $absence = Absence::findOrFail($id);

        $absence->delete();

        return back()->with('success', 'Période supprimée avec succès');

    }

    public function interventionsIndex() {
        $provider = Provider::findOrFail(Auth::user()->provider->id);

        $interventions = Intervention::where('provider_id', $provider->id)
                                        ->paginate(15);

        return view('provider.interventions-index', ['provider' => $provider, 'interventions' => $interventions]);
    }
    
}
