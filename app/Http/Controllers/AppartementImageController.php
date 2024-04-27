<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppartementImage;
use Illuminate\Support\Facades\Storage;

class AppartementImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(AppartementImage $appartementImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppartementImage $appartementImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppartementImage $appartementImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image = AppartementImage::findOrFail($id);

        Storage::disk('public')->delete($image->image);

        $image->delete();

        return redirect()->back()->with('success', 'L\'image a été supprimée avec succès.');
    }
}
