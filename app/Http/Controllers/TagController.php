<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::query()
            ->select(['id', 'name', 'valorisation_coeff'])
            ->latest()
            ->paginate(10);

        return view('admin.tags.index', [
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required', 'string'],
            'valorisation_coeff' => ['required', 'numeric', 'between:1,1.99']
        ]);

        $validateData['user_id'] = Auth()->id();
    
        $tag = new Tag($validateData);
        $tag->save();

        return redirect()->route('tags.index')
            ->with('success', "Tag créé avec succès");
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
    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return view('admin.tags.edit', [
            'tag' => $tag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tag = Tag::findOrFail($id);
        $validateData = $request->validate([
            'name' => ['required'],
            'valorisation_coeff' => ['required', 'numeric', 'between:1,1.99']
        ]);
    

        $tag->update($validateData);

        return redirect()->route('tags.index')
            ->with('success', "Tag mis à jour avec succès");
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {   
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->route('tags.index');
    }
}
