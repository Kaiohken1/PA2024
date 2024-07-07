<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAvis;
use App\Models\Appartement;
use App\Models\Reservation;
use App\Models\Intervention;
use Illuminate\Http\Request;

class UserAvisController extends Controller
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
    public function create(string $receive_user_id)
    {
   
     return view('user_avis.create', [
            'receive_user_id' => $receive_user_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $receiver_user_id, Request $request)
    {

        $validateData = $request->validate([
            'comment' => ['required', 'max:255'],
            'rating' => ['required', 'numeric', 'max:5']
        ]);

        $userAvis = new UserAvis($validateData);

        $userAvis->receiver_user_id = $receiver_user_id;
        $sender_user_id = Auth()->id();
        $userAvis->sender_user_id = $sender_user_id;
       
        $receiverUser = User::with('roles')
                            ->findOrFail($receiver_user_id);
                            
        if($receiverUser->roles->contains('nom', 'prestataire')){
            $alreadyintervened = Intervention::where('provider_id', $receiver_user_id)
                                                ->where('user_id', $sender_user_id)
                                                ->get();
            if($alreadyintervened->isNotEmpty()){
                $userAvis->save();
                return redirect()->route('users.show', ['user' => $receiver_user_id])
                ->with('success', "Avis créé avec succès");
            }
            else{
                return redirect()->route('users.show', ['user' => $receiver_user_id])
                ->with('error', "Vous ne pouvez pas donner votre avis sur ce prestataire");
            }

        }
        else{
            $receiver_reservation = Reservation::where('user_id', $receiver_user_id)->get();
            
            $sender_appartement = Appartement::where('user_id', $sender_user_id)->get();
            $alreadyReserved = $receiver_reservation->intersect($sender_appartement);
            if($alreadyReserved->isNotEmpty()){
                $userAvis->save();
                return redirect()->route('users.show', ['user' => $receiver_user_id])
                ->with('success', "Avis créé avec succès");
            }
            else{
                return redirect()->route('users.show', ['user' => $receiver_user_id])
                ->with('error', "Vous ne pouvez pas donner votre avis sur cet utilisateur");
            }
        }







        
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
        //
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
    public function destroy(string $receiver_user_id, string $user_avis_id)
    {
        
        $userAvis = UserAvis::findOrFail($user_avis_id); 
        $userAvis->delete();
        
        return redirect()->route('users.show', [
            'user' => $receiver_user_id
        ]);
    }
}
