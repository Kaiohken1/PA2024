<?php

namespace App\Http\Controllers\Provider;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ServiceParameter;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::query()
            ->select(['id', 'name', 'price'])
            ->latest()
            ->paginate(10);

        return view('services.index', ['services' => $services]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'price' => ['numeric'],
            'description' => ['required', 'string', 'max:255'],
        ]);
        
        Service::create($validatedData);

        return redirect()->route('services.index')->with('succes', 'Service créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return view('services.show', ['service' => $service]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('services.edit', ['service' => $service]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'price' => ['numeric'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $validatedData['flexPrice'] = $request->has('flexPrice') ? 1 : 0;

        if ($validatedData['flexPrice']) {
            $service->price = null;
        }

        $service->update($validatedData);

        return redirect()->route('services.edit', $service)
            ->with('success', "Service mis à jour avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Le service a été supprimé avec succès');
    }

    public function destroyParameter(Service $service, $id) {
        $serviceParameter = ServiceParameter::findOrFail($id);
        $serviceParameter->delete();

        return redirect()->route('services.edit', $service)
            ->with('success', "Paramètre supprimé avec succès");
    }
}
