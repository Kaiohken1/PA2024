<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appartement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PropertyValidated;
use Illuminate\Support\Facades\Notification;

class AppartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $appartements = Appartement::all()
        // ->latest()
        // ->paginate(10);

    return view('admin.property.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appartement = Appartement::withTrashed()->findOrFail($id);

        return view('admin.property.show', ['appartement' => $appartement]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appartement $appartement)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appartement $appartement)
    {


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $appartement = Appartement::findOrFail($id);
        
        foreach ($appartement->images as $image) {
            Storage::disk('public')->delete($image->image);
        }
        $appartement->delete();

        return redirect()->route('admin.property.index')
        ->with('success', 'Le bien a été refusé avec succès');
    }

    public function validateProperty($id)
    {
        $appartement = Appartement::findOrFail($id);

        $appartement->statut_id = 11;
        $appartement->update();

        // Notification::send($appartement->user, new PropertyValidated);
        
        return redirect()->route('admin.property.show', ['property' => $appartement])
        ->with('success', 'Le bien a été validé avec succès');
    }
}
