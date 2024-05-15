<?php

namespace App\Http\Controllers\Provider;

use App\Models\Service;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\ProviderDocument;
use App\Notifications\NewProvider;
use App\Http\Controllers\Controller;
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
            'email' => ['required'],
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
}
