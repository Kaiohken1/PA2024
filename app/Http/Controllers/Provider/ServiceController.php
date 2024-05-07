<?php

namespace App\Http\Controllers\Provider;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ServiceParameter;
use App\Http\Controllers\Controller;

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
            'name' => ['required', 'string', 'unique:services'],
            'price' => ['numeric'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $validatedData['flexPrice'] = $request->has('flexPrice') ? 1 : 0;

        $service = Service::create($validatedData);

        $dynamicInputs = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'input_') === 0) {
                $fieldId = explode('_', $key)[1];
    
                if (strpos($key, '_name') !== false) {
                    $dynamicInputs[$fieldId]['name'] = $value;
                } elseif (strpos($key, '_type') !== false) {
                    $dynamicInputs[$fieldId]['type'] = $value;
                }
            }
        }

        foreach ($dynamicInputs as $inputId => $input) {
            $serviceParameter = new ServiceParameter();
            $serviceParameter->name = $input['name'];
            $serviceParameter->data_type_id = $input['type'];
            $serviceParameter->service_id = $service->id;
            $serviceParameter->save();

            $service->parameters()->save($serviceParameter);
        }

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

        $dynamicInputs = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'input_') === 0) {
                $fieldId = explode('_', $key)[1];
    
                if (strpos($key, '_name') !== false) {
                    $dynamicInputs[$fieldId]['name'] = $value;
                } elseif (strpos($key, '_type') !== false) {
                    $dynamicInputs[$fieldId]['type'] = $value;
                }
            }
        }

        foreach ($dynamicInputs as $inputId => $input) {
            $serviceParameter = new ServiceParameter();
            $serviceParameter->name = $input['name'];
            $serviceParameter->data_type_id = $input['type'];
            $serviceParameter->service_id = $service->id;
            $serviceParameter->save();

            $service->parameters()->save($serviceParameter);
        }

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


    public function updateParameter(Request $request, Service $service, $id)
    {
        $validatedData = [];
    
        $inputData = $request->all();
    
        foreach ($inputData as $key => $value) {
            if (preg_match('/^input_(\d+)_name$/', $key, $matches)) {
                $validatedData["name"] = $value;
            } elseif (preg_match('/^input_(\d+)_type$/', $key, $matches)) {
                $validatedData["data_type_id"] = $value;
            }
        }   

        $serviceParameter = ServiceParameter::findOrFail($id);
    
        $serviceParameter->update($validatedData);
    
        return redirect()->route('services.edit', $service)
            ->with('success', "Paramètre mis à jour avec succès");
    }
}
