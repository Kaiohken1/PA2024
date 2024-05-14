<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Fermeture;
use App\Models\Appartement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\AppartementImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class AppartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validateData = $request->validate([
            'tag_id' => ['array'],
            'sort_type' => ['string']
        ]);

        

        $appartements = Appartement::query()
    ->select(['id', 'name', 'address', 'price', 'image', 'user_id'])
    ->with(['user:id,name'])
    ->with(['images:*']);

if (isset($validateData['tag_id'])) {
    $tags_id = $validateData['tag_id'];
    foreach($tags_id as $tag_id){
        $appartements->whereHas('tags', function ($query) use ($tag_id) {
            $query->where('tags.id', $tag_id);
        });
    }
    
}
if (isset($validateData['sort_type'])) {
    $sortType = $validateData['sort_type'];

    switch ($sortType) {
        case 'price_asc':
            $appartements->orderBy('price', 'asc');
            break;

        case 'price_desc':
            $appartements->orderBy('price', 'desc');
            break;

        case 'surface_asc':
            $appartements->orderBy('surface', 'asc');
            break;

        case 'surface_desc':
            $appartements->orderBy('surface', 'desc');
            break;
        
        case 'guest_count_asc':
            $appartements->orderBy('guestCount', 'asc');
            break;

        case 'guest_count_desc':
            $appartements->orderBy('guestCount', 'desc');
            break;
    } 
} else {
    $appartements->latest();
}
    



$appartements = $appartements->paginate(10);
        

//pour trier sur le prix ou sur la surface par exemple faire des bouttons avec fleche qui pointe 
// vers haut ou bas pour le order desc ou asc et renvoyer la value du btn dans un switch pour construire la querry avec le tri demandé

        $tags = Tag::all(); 

        return view('appartements.index', [
            'appartements' => $appartements,
            'tags' => $tags,
        ]);
        

       /* $appartements = Appartement::with('tags','images','user');
        return view('appartements.index', [
            'appartements' => $appartements
        ]);
        */
    }

    public function userIndex()
{
    $user = Auth::user();

    $appartements = $user->appartement;
    
    return view('appartements.userIndex', [
        'appartements' => $appartements
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();    
        return view('appartements.create',[
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
            'address' => ['required', 'max:255'],
            'surface' => ['required', 'numeric'],   
            'guestCount' => ['required', 'numeric'],   
            'roomCount' => ['required', 'numeric'],   
            'description' => ['required', 'max:255'],   
            'price' => ['required', 'numeric'],
            'image' => ['array'],
            'image.*' => ['image'],
            'tag_id' => ['array']
        ]);

        unset($validateData['image']);
    
        $validateData['user_id'] = Auth()->id();
    
        $appartement = new Appartement($validateData);
        
        $appartement->user()->associate($validateData['user_id']);
        $appartement->save();
        if(isset($validateData['tag_id'])){
            $appartement->tags()->sync($validateData['tag_id']);
        }

        if ($request->hasFile('image')) {
            $images = $request->file('image');
            
            foreach ($images as $image) {
                $path = $image->store('imagesAppart', 'public');
                
                $appartementImage = new AppartementImage();
                $appartementImage->image = $path;
                $appartementImage->appartement_id = $appartement->id; 
                $appartementImage->save();
            }
        }
         
    
        return redirect()->route('property.index')
            ->with('success', "Appartement créé avec succès");
    }    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appartement = Appartement::findOrFail($id);

        $intervalle = Reservation::where("appartement_id", $appartement->id)
            ->select("start_time","end_time")
            ->get();

        $fermeture = Fermeture::where("appartement_id", $appartement->id)
            ->select("start_time","end_time")
            ->get();

                    // Récupérer les dates déjà réservées pour cet appartement
        $reservedDates = Reservation::where('appartement_id', $id)
        ->get() // Récupérez toutes les réservations
        ->map(function ($reservation) {
            return [
                'start' => Carbon::parse($reservation->start_time)->toDateString(),
                'end' => Carbon::parse($reservation->end_time)->toDateString(),
            ];
        })
        ->toArray();

        return view('appartements.show', [
            'appartement' => $appartement,
            'fermetures' => $fermeture,
            'intervalles' => $intervalle,
            'reservedDates' => $reservedDates
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appartement = Appartement::findOrFail($id);

        Gate::authorize('update', $appartement);


        $appartement = Appartement::findOrFail($id);
        $tags = Tag::all()->where("user_id", Auth()->id());
        return view('appartements.edit', [
            'appartement' => $appartement,
            'tags' => $tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $appartement = Appartement::findOrFail($id);

        Gate::authorize('update', $appartement);

        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'address' => ['required', 'max:255'],
            'surface' => ['required', 'numeric', 'min:0'], 
            'guestCount' => ['required', 'numeric', 'min:0'], 
            'roomCount' => ['required', 'numeric', 'min:0'], 
            'description' => ['required', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'], 
            'image' => ['array'],
            'image.*' => ['image'],
            'tag_id' => ['array'],
        ]);
        

        unset($validatedData['image']);

        if ($request->hasFile('image')) {
            $images = $request->file('image');
            
            $appartementImages = AppartementImage::where('appartement_id', $appartement->id)->get();
    
            if($appartementImages->count() >= 4) {
                return redirect()->route('property.edit', $appartement->id)
                    ->with('error', "Il y a déjà 4 images pour votre appartement. Pour en ajouter une nouvelle, veuillez en supprimer une autre.");
            }

            foreach ($images as $image) {
                $path = $image->store('imagesAppart', 'public');
                
                $appartementImage = new AppartementImage();
                $appartementImage->image = $path;
                $appartementImage->appartement_id = $appartement->id;
                $appartementImage->save();
            }
        }  
    
        $appartement->update($validatedData);

        if(isset($validatedData['tag_id'])){
            $appartement->tags()->sync($validatedData['tag_id']);
        } else {
                $appartement->tags()->detach();
        }
    
        return redirect()->route('property.edit', $appartement->id)
            ->with('success', "Appartement mis à jour avec succès");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) : RedirectResponse
    {
        $appartement = Appartement::findOrFail($id);

        Gate::authorize('delete', $appartement);

        $appartement->delete();

        return redirect(url('/'));
    }

    public function destroyImg($id) : RedirectResponse {
        $appartementImages = AppartementImage::findOrFail($id);

        $appartementImages->delete();

        return redirect()->route('property.edit', $appartementImages->appartement_id)
        ->with('success', "Appartement mis à jour avec succès");
    }
}
