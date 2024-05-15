<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::query()
            ->select('id', 'name')
            ->latest()
            ->paginate(10);

        return view('services.documents.index', ['documents' => $documents]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'unique:documents'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        Document::create($validatedData);

        return redirect()->route('documents.index')->with('succes', 'Type de document créé avec succès');

    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        return view('services.documents.edit', ['document' => $document]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $validatedData = $request->validate([
            'name' => ['string'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $document->update($validatedData);

        return redirect()->route('documents.index')->with('succes', 'Type de document modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Le document a été supprimé avec succès');
    }
}
