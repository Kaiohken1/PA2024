<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function create()
    {
        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|unique:services',
            'description' => 'nullable|string',
        ]);

        Service::create([
            'nom' => $request->input('nom'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.service.index')->with('success', 'Service ajouté avec succès.');
    }
}
