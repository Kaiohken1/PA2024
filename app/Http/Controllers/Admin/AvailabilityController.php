<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Intervention;

class AvailabilityController extends Controller
{
    public function availableProviders(Request $request)
    {

        $request->validate([
            'start' => 'required|date|after:now',
            'end' => 'nullable|date|after:start_time',
            'service_id' => 'required|exists:services,id',
            'intervention_id' => 'required|exists:interventions,id',
        ]);


        $start = $request->input('start');
        $end = $request->input('end');
        $serviceId = $request->input('service_id');
        $interventionId = $request->input('intervention_id');

        $intervention = Intervention::findOrfail($interventionId);

        $providers = Service::findOrFail($serviceId)->provider;

        $availableProviders = $providers->filter(function ($provider) use ($start) {
            return !$provider->intervention_events()->whereDate('start', $start)->exists();
        });
        
        
        return view('provider.availability', ['providers' => $availableProviders, 'intervention' => $intervention]);
    }
}
