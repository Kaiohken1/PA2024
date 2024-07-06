<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Conversation;
use App\Models\Intervention;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::query()
            ->select(['id', 'name', 'email', 'statut'])
            ->latest()
            ->paginate(10);

        return view('admin.providers.index', [
            'providers' => $providers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $services = Service::query()
    //         ->select(['id', 'name'])
    //         ->get();

    //     return view('provider.create', [
    //         'services' => $services
    //     ]);
    // }

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
    public function show($id)
    {
        $provider = Provider::withTrashed()->findOrFail($id);
        return view('admin.providers.show', ['provider' => $provider, 'service' => $provider->services->first()]);
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

        return redirect()->route('admin.providers.index')
            ->with('success', 'Le prestataire a été supprimé avec succès');
    }

    public function validateProvider($id)
    {
        Provider::where('id', $id)->update(['statut' => 'Validé']);

        $provider = Provider::find($id);

        if ($provider) {            
            $role = Role::where('nom', 'prestataire')->first();
            if ($role) {
                $provider->user->roles()->sync([$role->id]);
            }
        }
        

        return redirect()->route('admin.providers.index')
            ->with('success', 'Le prestataire a été validé avec succès');
    }


    public function availableProviders($id) {

        $intervention = Intervention::findOrFail($id);

        $providers = $intervention->estimations
        ->filter(function ($estimation) {
            return $estimation->statut_id == 1;
        })
        ->map(function ($estimation) {
            return $estimation->provider;
        })
        ->unique();
    
        
        return view('provider.availability', ['id' => $intervention->id, 'intervention' => $intervention,'providers' => $providers]);
    }


    public function message($userId)
    {
      //  $createdConversation =   Conversation::updateOrCreate(['sender_id' => auth()->id(), 'receiver_id' => $userId]);

      $authenticatedUserId = auth()->id();

      $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
                $query->where('sender_id', $authenticatedUserId)
                    ->where('receiver_id', $userId);
                })
            ->orWhere(function ($query) use ($authenticatedUserId, $userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $authenticatedUserId);
            })->first();
        
      if ($existingConversation) {
        return redirect()->route('admin.chat', ['query' => $existingConversation->id]);
      }
  
      $createdConversation = Conversation::create([
          'sender_id' => $authenticatedUserId,
          'receiver_id' => $userId,
      ]);
 
        return redirect()->route('chat', ['query' => $createdConversation->id]);
        
    }
}
