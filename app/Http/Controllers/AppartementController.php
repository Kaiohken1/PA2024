<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\User;
use App\Models\Fermeture;
use App\Models\Appartement;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\AppartementAvis;
use App\Models\AppartementImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use MBarlow\Megaphone\Types\Important;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

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
    
        $appartementsQuery = Appartement::query()
            ->select(['id', 'name', 'address', 'price', 'image', 'user_id'])
            ->where('active_flag', 1)
            ->with(['user:id,name'])
            ->with(['avis'])
            ->with(['images:*'])
            ->withCount('avis')
            ->withAvg('avis', 'rating_cleanness')
            ->withAvg('avis', 'rating_price_quality')
            ->withAvg('avis', 'rating_location')
            ->withAvg('avis', 'rating_communication');
    
        if (isset($validateData['tag_id'])) {
            $tags_id = $validateData['tag_id'];
            foreach ($tags_id as $tag_id) {
                $appartementsQuery->whereHas('tags', function ($query) use ($tag_id) {
                    $query->where('tags.id', $tag_id);
                });
            }
        }
    
        if (isset($validateData['sort_type'])) {
            $sortType = $validateData['sort_type'];
            
            switch ($sortType) {
                case 'price_asc':
                    $appartementsQuery->orderBy('price', 'asc');
                    break;
    
                case 'price_desc':
                    $appartementsQuery->orderBy('price', 'desc');
                    break;
    
                case 'surface_asc':
                    $appartementsQuery->orderBy('surface', 'asc');
                    break;
    
                case 'surface_desc':
                    $appartementsQuery->orderBy('surface', 'desc');
                    break;
    
                case 'guest_count_asc':
                    $appartementsQuery->orderBy('guestCount', 'asc');
                    break;
    
                case 'guest_count_desc':
                    $appartementsQuery->orderBy('guestCount', 'desc');
                    break;
            }
        } else {
            $appartementsQuery->latest();
        }
    
        $appartements = $appartementsQuery->paginate(10);
    
        $appartements->getCollection()->transform(function ($appartement) {
            $appartement->overall_rating = (
                $appartement->avis_avg_rating_cleanness +
                $appartement->avis_avg_rating_price_quality +
                $appartement->avis_avg_rating_location +
                $appartement->avis_avg_rating_communication
            ) / 4;
            return $appartement;
        });
    
        $mostReserved = Appartement::withCount('reservations')
            ->where('active_flag', 1)
            ->withCount('avis')
            ->withAvg('avis', 'rating_cleanness')
            ->withAvg('avis', 'rating_price_quality')
            ->withAvg('avis', 'rating_location')
            ->withAvg('avis', 'rating_communication')
            ->orderBy('reservations_count', 'desc')
            ->take(4)
            ->get();
    
        $mostReserved->each(function ($appartement) {
            $appartement->overall_rating = (
                $appartement->avis_avg_rating_cleanness +
                $appartement->avis_avg_rating_price_quality +
                $appartement->avis_avg_rating_location +
                $appartement->avis_avg_rating_communication
            ) / 4;
            return $appartement;
        });
    
        $bestRated = Appartement::withCount('avis')
            ->where('active_flag', 1)
            ->withAvg('avis', 'rating_cleanness')
            ->withAvg('avis', 'rating_price_quality')
            ->withAvg('avis', 'rating_location')
            ->withAvg('avis', 'rating_communication')
            ->take(4)
            ->get();
    
        $bestRated->each(function ($appartement) {
            $appartement->overall_rating = (
                $appartement->avis_avg_rating_cleanness +
                $appartement->avis_avg_rating_price_quality +
                $appartement->avis_avg_rating_location +
                $appartement->avis_avg_rating_communication
            ) / 4;
            return $appartement;
        });
    
        $bestRated = $bestRated->sortByDesc('overall_rating')->take(10);
    
        $tags = Tag::all();
    
        return view('appartements.index', [
            'appartements' => $appartements,
            'mostReserved' => $mostReserved,
            'bestRated' => $bestRated,
            'tags' => $tags,
        ]);
    }
    

    public function userIndex()
    {

        $appartements = Appartement::query()
                        ->where('user_id', Auth::user()->id)
                        ->where('statut_id', 11)
                        ->paginate(3);

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
        return view('appartements.create', [
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required', 'max:140'],
            'address' => ['required', 'max:255'],
            'surface' => ['required', 'numeric'],
            'guestCount' => ['required', 'numeric'],
            'roomCount' => ['required', 'numeric'],
            'description' => ['required', 'max:255'],
            'price' => ['required', 'numeric'],
            'image' => ['array'],
            'image.*' => ['image'],
            'tag_id' => ['array'],
            'property_type' => ['string', 'required'],
            'city' => ['string', 'required'],
            'location_type' => ['string', 'required'],
            'postal_code' => ['integer', 'regex:/^[0-9]{5}$/','required'],
            'iban' => ['nullable', 'mimes:jpg,png,pdf']
        ]);

        $images = $request->file('image');
        if (count($images) < 4) {
            return back()->withErrors(['image' => 'Vous devez télécharger au moins 4 images.'])->withInput();
        }

        unset($validateData['image']);

        $validateData['user_id'] = Auth()->id();

        $appartement = new Appartement($validateData);

        $appartement->user()->associate($validateData['user_id']);
        $appartement->statut_id = 1;
        $appartement->save();
        if (isset($validateData['tag_id'])) {
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

        if ($request->hasFile('iban')) {
            $path = $request->file('iban')->store('providerDocs', 'public');
            $validateData['iban'] = $path;

            $user = User::find(Auth::id());
            $user->iban = $validateData['iban'];
            $user->save();
        }

        $admins = User::whereHas('roles', function ($query) {
            $query->where('nom', 'admin');
        })->get();

        $notification = new Important(
            'Nouvelle demande d\'inscription de logement',
            'Un nouveau bien doit passer par une validation de location',
            url('https://admin.paris-caretaker-services.store/admin/property/' . $appartement->id),
            'Voir la demande'
        );

        Notification::send($admins, $notification);  


        return redirect()->route('property.index')
            ->with('success', "Appartement créé avec succès et en attente de validation");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        $hasPremiumSubscription = $this->hasExploratorSubscription($user);
    
        $appartement = Appartement::with('avis')
            ->withAvg('avis', 'rating_cleanness')
            ->withAvg('avis', 'rating_price_quality')
            ->withAvg('avis', 'rating_location')
            ->withAvg('avis', 'rating_communication')
            ->withCount('avis')
            ->findOrFail($id);
    
        $total_avg = ($appartement->avis_avg_rating_cleanness +
            $appartement->avis_avg_rating_price_quality +
            $appartement->avis_avg_rating_location +
            $appartement->avis_avg_rating_communication) / 4;
    
        $appartement->overall_rating = round($total_avg, 2);
    
        $intervalles = Reservation::where("appartement_id", $appartement->id)
            ->select("start_time", "end_time")
            ->get();
    
        $fermetures = Fermeture::where("appartement_id", $appartement->id)
            ->select("start", "end")
            ->get();
    
        $reservedDates = Reservation::where('appartement_id', $id)
            ->get()
            ->map(function ($reservation) {
                return [
                    'start' => Carbon::parse($reservation->start_time)->toDateString(),
                    'end' => Carbon::parse($reservation->end_time)->toDateString(),
                ];
            })
            ->toArray();
    
        $appartementAvisSessionUser = AppartementAvis::where('appartement_id', $id)
            ->where('user_id', auth()->id())
            ->get();
    
        $appartementAvisOtherUser = AppartementAvis::where('appartement_id', $id)
            ->latest()
            ->where('user_id', '!=', auth()->id())
            ->get();
    
        $appartementAvis = $appartementAvisSessionUser->merge($appartementAvisOtherUser);
    
        $appartementAvis = $appartementAvis->each(function ($avis) {
            $avis->voyageur_rating = round(($avis->rating_cleanness + $avis->rating_price_quality + $avis->rating_location + $avis->rating_communication) / 4, 0);
            return $avis;
        });
    
        $dateInBase = [];
    
        foreach ($fermetures as $fermeture) {
            $dateInBase[] = [
                'from' => date("d-m-Y", strtotime($fermeture->start)),
                'to' => date("d-m-Y", strtotime($fermeture->end))
            ];
        }
    
        foreach ($intervalles as $intervalle) {
            $dateInBase[] = [
                'from' => date("d-m-Y", strtotime($intervalle->start_time)),
                'to' => date("d-m-Y", strtotime($intervalle->end_time))
            ];
        }
    
        $mainImages = $appartement->images()->where('is_main', true)->orderBy('main_order')->take(4)->get();
    
        $rest = 4 - $mainImages->count();
    
        $otherImages = $appartement->images()->where('is_main', false)->take($rest)->get();
    
        $propertyImages = $mainImages->merge($otherImages);
    
        return view('appartements.show', [
            'appartement' => $appartement,
            'fermetures' => $fermetures,
            'intervalles' => $intervalles,
            'reservedDates' => $reservedDates,
            'appartementAvis' => $appartementAvis,
            'datesInBase' => $dateInBase,
            'propertyImages' => $propertyImages,
            'hasPremiumSubscription' => $hasPremiumSubscription
        ]);
    }
    
    private function hasExploratorSubscription($user)
    {
        $exploratorKeys = [
            env('STRIPE_PRICE_PREMIUM_MONTHLY'),
            env('STRIPE_PRICE_PREMIUM_YEARLY')
        ];
    
        $subscription = $user->subscriptions()->where('stripe_status', 'active')->first();
        if ($subscription && in_array($subscription->stripe_price, $exploratorKeys)) {
            return true;
        }
        return false;
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appartement = Appartement::findOrFail($id);

        Gate::authorize('update', $appartement);


        $appartement = Appartement::findOrFail($id);
        $tags = Tag::all();
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
            // 'address' => ['required', 'max:255'],
            'surface' => ['required', 'numeric', 'min:0'],
            'guestCount' => ['required', 'numeric', 'min:0'],
            'roomCount' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['array'],
            'image.*' => ['image'],
            'tag_id' => ['array'],
            'property_type' => ['string', 'required'],
            // 'city' => ['string', 'required'],
            'location_type' => ['string', 'required'],
            // 'postal_code' => ['integer', 'regex:/^[0-9]{5}$/','required'],
        ]);

        $appartementImages = AppartementImage::where('appartement_id', $appartement->id)->get();

        if ($appartementImages->count() >= 15) {
            return redirect()->route('property.edit', $appartement->id)
                ->with('error', "Il y a déjà 15 images pour votre appartement. Pour en ajouter une nouvelle, veuillez en supprimer une autre.");
        }

        unset($validatedData['image']);

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

        $appartement->update($validatedData);
        if (isset($validatedData['tag_id'])) {
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
    public function destroy($id): RedirectResponse
    {
        $appartement = Appartement::findOrFail($id);

        Gate::authorize('delete', $appartement);

        $appartement->statut_id = 12;

        $appartement->delete();

        return redirect()->route('dashboard')
        ->with('success', "Appartement supprimé");
    }

    public function destroyImg($id): RedirectResponse
    {
        $appartementImages = AppartementImage::findOrFail($id);

        $appartementImages->delete();

        return redirect()->route('property.edit', $appartementImages->appartement_id)
            ->with('success', "Appartement mis à jour avec succès");
    }

    public function updateActiveFlag($id) {
        $appartement = Appartement::findOrFail($id);
        $appartement->active_flag == 1 ? $appartement->active_flag = 0 : $appartement->active_flag = 1;
        $appartement->update();

        return redirect()->route('dashboard')
        ->with('success', "Statut de l'appartement mis à jour avec succès");
    }
}
