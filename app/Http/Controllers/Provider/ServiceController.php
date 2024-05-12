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
            ->select(['id', 'name', 'price', 'flexPrice', 'active_flag'])
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
            'documentsId' => ['array'],
            'documentsId*' => ['exists:documents,id'],
        ]);

        $validatedData['flexPrice'] = $request->has('flexPrice') ? 1 : 0;

        $dynamicInputs = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'input_') === 0) {
                $fieldId = explode('_', $key)[1];

                $validationRules = [
                    "input_{$fieldId}_name" => ['required', 'string'],
                    "input_{$fieldId}_type" => ['required', 'exists:data_types,id'],
                ];
                $validatedDynamicData = $request->validate($validationRules);
                $dynamicInputs[$fieldId]['name'] = $validatedDynamicData["input_{$fieldId}_name"];
                $dynamicInputs[$fieldId]['type'] = $validatedDynamicData["input_{$fieldId}_type"];

            }
        }
        $service = Service::create($validatedData);

        if($request->has('documentsId')) {
            foreach ($request->documentsId as $documentId) {
                $service->documents()->attach($documentId);
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
            'documentsId' => ['array'],
            'documentsId*' => ['exists:documents,id'],
        ]);

        $validatedData['flexPrice'] = $request->has('flexPrice') ? 1 : 0;

        if ($validatedData['flexPrice']) {
            $service->price = null;
        }

        $dynamicInputs = [];
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'input_') === 0) {
                $fieldId = explode('_', $key)[1];

                $validationRules = [
                    "input_{$fieldId}_name" => ['required', 'string'],
                    "input_{$fieldId}_type" => ['required', 'exists:data_types,id'],
                ];
                $validatedDynamicData = $request->validate($validationRules);
                $dynamicInputs[$fieldId]['name'] = $validatedDynamicData["input_{$fieldId}_name"];
                $dynamicInputs[$fieldId]['type'] = $validatedDynamicData["input_{$fieldId}_type"];
            }
        }

        $service->update($validatedData);

        if($request->has('documentsId')) {
            foreach ($request->documentsId as $documentId) {
                $service->documents()->attach($documentId);
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

    public function destroyParameter(Service $service, $id)
    {
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
            if (strpos($key, 'input_') === 0) {
                $fieldId = explode('_', $key)[1];

                $validationRules = [
                    "input_{$fieldId}_name" => ['required', 'string'],
                    "input_{$fieldId}_type" => ['required', 'exists:data_types,id'],
                ];
                $validatedDynamicData = $request->validate($validationRules);
                $inputData[$fieldId]['name'] = $validatedDynamicData["input_{$fieldId}_name"];
                $inputData[$fieldId]['data_type_id'] = $validatedDynamicData["input_{$fieldId}_type"];
            }
        }

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


    public function destroyDocument(Service $service, $id)
    {
        $service->documents()->detach($id);

        return redirect()->route('services.edit', $service)
            ->with('success', "Document supprimé avec succès");
    }

    public function updateDocument(Service $service, $id, Request $request) {
        $validatedData = $request->validate([
            'new_document_id' => ['required', 'exists:documents,id'],
        ]);
    
        $service->documents()->updateExistingPivot($id, ['document_id' => $validatedData['new_document_id']]);
        
        return redirect()->route('services.edit', $service)
            ->with('success', "Document modifié avec succès");
    }

    public function updateActive($id) {
        $service = Service::findOrfail($id);
        $service->active_flag = $service->active_flag ? 0 : 1;
        $service->save();
    
        return redirect()->back()->with('success', 'Statut du service ' . $service->name . " modifié avec succès");
    }
}
