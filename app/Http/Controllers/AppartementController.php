<?php

namespace App\Http\Controllers;

use App\Models\Appartement;
use Illuminate\Http\Request;

class AppartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appartements = Appartement::query()
            ->select()
            ->latest()
            ->paginate(5);
        return view('appartement.index', compact('appartements'));
    }

    public function create()
    {
        return view('appartement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titre' => ['required'],
            'voyageurs' => ['required'],
            'prix' => ['required'],
            'superficie' => ['required'],
            'adresse' => ['required']
        ]);

        Appartement::create($validatedData);
        return redirect()->route('appartements.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    $appartement = Appartement::find($id);

    return view('appartement.show', [
        'appartement' => $appartement,
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $appartement = Appartement::find($id);
    
    return view('appartement.edit', [
        'appartement' => $appartement,
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id->delete();

        return redirect()->route('appartements.index');
    }
}
