<?php

namespace App\Http\Controllers\Provider;

use App\Models\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
            'flexPrice' => ['boolean'],
            'habilitationImg' => ['image'],
            'provider_description' => ['max:255'],    
        ]);

        $validateData['user_id'] = Auth()->id();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('providerPfp', 'public');
            $validateData['image'] = $path;
        }

        $provider = new Provider($validateData);
        $provider->user()->associate($validateData['user_id']);

        $provider->save();

        $provider->services()->attach($validateData['service_id'], [
            'price' => $validateData['price'],
            'flexPrice' => $request->has('flexPrice'),
            'habilitationImg' => $validateData['habilitationImg'] ?? null,
            'description' => $validateData['provider_description'],
        ]);

        if ($request->hasFile('habilitationImg')) {
            $path = $request->file('habilitationImg')->store('providerHabilitations', 'public');
            $validateData['habilitationImg'] = $path;
        }

        return redirect('/')
            ->with('success', "Votre demande a bien été prise en compte, elle sera soumise à validation par un administrateur");
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        //
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
        //
    }
}
