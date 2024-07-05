<?php

namespace App\Http\Controllers\Provider;

use Carbon\Carbon;
use App\Models\Absence;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Intervention;
use Illuminate\Http\Request;
use App\Models\ProviderDocument;
use App\Notifications\NewProvider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\InterventionEstimation;
use App\Models\InterventionEvent;
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
        if(!Auth::check()) {
            return redirect()->route('provider-register');
        }
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

        Auth::logout();

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

    public function totalGains($id): float
    {
        $provider = Provider::findOrFail($id);

        return $provider->interventions()
                        ->whereIn('statut_id', [5, 3])
                        ->get()
                        ->reduce(function ($carry, $intervention) {
                            return $carry + ($intervention->price - $intervention->commission);
                        }, 0);
    }

    public function monthlyGains($id, int $month = null, int $year = null): float
    {
        $provider = Provider::findOrFail($id);
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;
        return $provider->interventions()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->whereIn('statut_id', [5, 3])
                    ->get()
                    ->reduce(function ($carry, $intervention) {
                        return $carry + ($intervention->price - $intervention->commission);
                    }, 0);
    }


    public function home()
    {
        $provider = Provider::findOrFail(Auth::user()->provider->id);
        $totalGains = $this->totalGains($provider->id);
        $monthlyGains = $this->monthlyGains($provider->id);

        Carbon::setLocale('fr');
        $currentMonthYear = Carbon::now()->translatedFormat('F Y');

        $providerId = $provider->id;

        $proposals = Intervention::query()
                            ->where('service_id', $provider->services->first()->id)
                            ->where('statut_id', 1)
                            ->where(function($query) use ($providerId) {
                                        $query->where('provider_id', NULL)
                                      ->orWhere('provider_id', $providerId);
                            })
                            ->latest()
                            ->take(5)
                            ->get();

        $absences = Absence::where('provider_id', $provider->id)
                            ->take(5)
                            ->get();

        return view('provider.home', [
            'provider' => $provider,
            'totalGains' => $totalGains,
            'monthlyGains' => $monthlyGains,
            'currentMonthYear' => $currentMonthYear,
            'proposals' => $proposals,
            'absences' => $absences,
        ]);
    }

    public function proposals() {
        $provider = Provider::findOrFail(Auth::user()->provider->id);

        return view('provider.proposals');
    }

    public function calendar() {
        $provider = Provider::findOrFail(Auth::user()->provider->id);
        return view('provider.calandar', ['provider' => $provider]);
    }

    public function availability() {
        $provider = Provider::findOrFail(Auth::user()->provider->id);
        $absences = Absence::query()
                    ->where('provider_id', $provider->id)
                    ->get();

        $interventions = InterventionEvent::query()
                        ->where('provider_id', $provider->id)
                        ->get();
    
        $datesInBase = [];
    
        foreach ($absences as $absence) {
            $datesInBase[] = [
                'from' => date("d-m-Y", strtotime($absence->start)),
                'to' => date("d-m-Y", strtotime($absence->end))
            ];
        }

        foreach ($interventions as $intervention) {
            $datesInBase[] = [
                'from' => date("d-m-Y", strtotime($intervention->start)),
                'to' => date("d-m-Y", strtotime($intervention->end))
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
