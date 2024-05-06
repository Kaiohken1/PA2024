<?php

namespace App\Http\Controllers\Provider;

use App\Models\Service;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewProvider;
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
            'email' => ['required'],
            'description' => ['required', 'max:255'],
            'avatar' => ['image'],  
            'service_id' => ['required', 'exists:services,id'],
            'price' => ['required', 'numeric'],
            'habilitationImg' => ['required', 'image'],
            'provider_description' => ['max:255'],    
        ]);

        $validateData['user_id'] = Auth()->id();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('providerPfp', 'public');
            $validateData['avatar'] = $path;
        }

        $provider = new Provider($validateData);
        $provider->user()->associate($validateData['user_id']);

        $provider->save();

        $validateData['flexPrice'] = $request->has('flexPrice') ? 1 : 0;

        if ($request->hasFile('habilitationImg')) {
            $path = $request->file('habilitationImg')->store('providerHabilitations', 'public');
            $validateData['habilitationImg'] = $path;
        }
        
        $provider->services()->attach($validateData['service_id'], [
            'price' => $validateData['price'],
            'flexPrice' => $validateData['flexPrice'],
            'habilitationImg' => $validateData['habilitationImg'],
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
        return view('provider.show', ['provider' => $provider, 'services' => $provider->services]);
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

        $provider->services()->withPivot('habilitationImg')->get();
        
        if ($provider->avatar !== NULL) {
            Storage::delete([$provider->avatar]);
        }

        $provider->delete();

        return redirect()->route('providers.index')
            ->with('success', 'Le prestataire a été supprimé avec succès');
    }

    public function validateProvider($id) {

        Provider::where('id', $id)->update(['statut' => 'Validé']);
    
        return redirect()->route('providers.index')
            ->with('success', 'Le prestataire a été validé avec succès');
    }    
}
