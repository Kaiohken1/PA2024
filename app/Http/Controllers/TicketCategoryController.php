<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketCategories = TicketCategory::all();
        return view('admin.ticket-categories.index', [
            'ticketCategories' => $ticketCategories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ticket-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'category' => ['required', 'max:255'],
        ]);
        
    
        $ticketCategory = new TicketCategory($validateData);
        $ticketCategory->save();

        return redirect()->route('ticket-categories.index')
            ->with('success', "Catégorie créée avec succès");

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticketCategory = TicketCategory::findOrFail($id);
        return view('admin.ticket-categories.edit', [
            'ticketCategory' => $ticketCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticketCategory = TicketCategory::findOrFail($id);
        $validateData = $request->validate([
            'category' => ['required', 'max:255']
        ]);
    

        $ticketCategory->update($validateData);

        return redirect()->route('ticket-categories.index')
            ->with('success', "Catégorie mise à jour avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticketCategory = TicketCategory::findOrFail($id);
        $ticketCategory->delete();

        return redirect()->route('ticket-categories.index')
            ->with('success', "Catégorie supprimée avec succès");
    }
}
