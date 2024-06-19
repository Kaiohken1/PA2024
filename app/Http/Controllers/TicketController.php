<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeUnit\FunctionUnit;

class TicketController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $tickets = Ticket::query()->with(['ticketCategory'])
                       ->where('asker_user_id', $user_id)
                       ->get();
                       

        return view('ticket.index', [
            'tickets' => $tickets
        ]);  

        
    }

    public function create()
    {
        $ticketCategories = TicketCategory::all();

        return view('ticket.create', [
            'ticketCategories' => $ticketCategories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|numeric'
        ]);


        $ticket = new Ticket($request->all());
        $ticket->asker_user_id = auth()->id();
        $ticket->save();

        return redirect()->route('tickets.index')->with('status', 'Ticket créer avec succès');
    }

    public function show(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        

        return view('ticket.show', [
            'ticket' => $ticket
        ]);  

    }

    public function edit(string $id)
    {
        $ticketCategories = TicketCategory::all();
        $ticket = Ticket::findOrFail($id);
        

        return view('ticket.edit', [
            'ticketCategories' => $ticketCategories,
            'ticket' => $ticket
        ]);  

    }

    public function update(string $id, Request $request)
    {
        $ticket = Ticket::findOrFail($id);
        $validateData = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|numeric'
        ]);


        if ($ticket->attributed_role_id == null and $ticket->attributed_user_id == null){
            $ticket->update($validateData);
            return redirect()->route('tickets.index')
            ->with('status', 'Ticket mis à jour avec succès');
        } else{
            return redirect()->route('tickets.index')
            ->with('error', 'La mis à jour du ticket n\'est plus possible');
        }

        

    }

    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->attributed_role_id == null and $ticket->attributed_user_id == null){
            $ticket->delete();
            return redirect()->route('tickets.index')
            ->with('status', 'Ticket supprimé avec succès');
        } else{
            return redirect()->route('tickets.index')
        ->with('status', 'La suppression du ticket n\'est plus possible');
        }


        
        

        

    }

    public function apiIndexAttributor(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->with('roles')->first();
        if (!($user->roles->contains('nom', 'admin'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    

        $tickets = Ticket::with('attributedUser')
                            ->with('attributedRole')
                            ->with('ticketCategory')
                            ->where('attributed_role_id', null)
                            ->get();
                            
        Log::info('Tickets sent', ['tickets' => $tickets]);

        foreach ($tickets as $ticket){
            $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
            Log::info('updated', ['validate' => $ticket->formatted_date]);
        }
        
        return response()->json($tickets);
    }

    public function apiShowAttributor(string $ticket_id, Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'admin'))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $ticket = Ticket::with('attributedUser')
                        ->with('attributedRole')
                        ->with('ticketCategory')
                        ->findOrFail($ticket_id);
    $roles = Role::get();
                        
    Log::info('Ticket sent', ['ticket' => $ticket]);
    Log::info('Roles sent', ['roles' => $roles]);

    
        $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
        Log::info('updated', ['validate' => $ticket->formatted_date]);
   

    return response()->json([
        'ticket' => $ticket,
        'roles' => $roles
    ]);
}

public function apiUpdateAttributor(string $ticket_id, Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'admin'))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    

    $ticket = Ticket::findOrfail($ticket_id);
    $validateData = $request->validate([
        'attributed_role_id' => ['required', 'numeric'],
        'priority' => ['required', 'numeric']
    ]);
    $validateData['attributed_user_id'] = null;
    Log::info('updated', ['request' => $request->all()]);
    
    $ticket->update($validateData);
    Log::info('updated', ['validate' => $validateData]);

}

public function apiIndexRoles(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->with('roles')->first();
        if (!($user->roles->contains('nom', 'PCS'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user_roles_id = [];
        foreach ($user->roles as $role){
           array_push($user_roles_id, $role->id);
        }
        Log::info('Tickets sent', ['array' => $user_roles_id]);

        $tickets = Ticket::query()
        ->with('attributedUser')
        ->with('attributedRole')
        ->with('ticketCategory')
        ->whereIn('attributed_role_id', $user_roles_id)
        ->where('attributed_user_id', null)
        ->get();

        foreach ($tickets as $ticket){
            $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
            Log::info('updated', ['validate' => $ticket->formatted_date]);
        }
                            
        
        return response()->json($tickets);
    }

    public function apiShowRoles(string $ticket_id, Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'PCS'))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $ticket = Ticket::with('attributedUser')
                        ->with('attributedRole')
                        ->with('ticketCategory')
                        ->findOrFail($ticket_id);
                        
    Log::info('Ticket sent', ['FEURFEUR TICKET' => $ticket]);

    
    $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
    Log::info('updated', ['validate' => $ticket->formatted_date]);

    return response()->json([
        'ticket' => $ticket
    ]);
}

public function apiUpdateRoles(string $ticket_id, Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'PCS'))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $ticket = Ticket::findOrfail($ticket_id);
    $ticket->attributed_user_id = $user->id;

    Log::info('updated', ['request' => $request->all()]);
    Log::info('updated', ['user' => $user]);
    $ticket->update();

}

public function apiIndexHelper(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->with('roles')->first();
        if (!($user->roles->contains('nom', 'PCS'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user_roles_id = [];
        foreach ($user->roles as $role){
           array_push($user_roles_id, $role->id);
        }


        $tickets = Ticket::query()
        ->with('attributedUser')
        ->with('attributedRole')
        ->with('ticketCategory')
        ->where('attributed_user_id', $user->id)
        ->whereNotIn('status', ['Résolu', 'Rejeté'])
        ->get();
        Log::info('FEURFEUR', [$tickets]);
        
        foreach ($tickets as $ticket){
            $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
            Log::info('updated', ['validate' => $ticket->formatted_date]);
        }
                            
        
        return response()->json($tickets);
    }

    public function apiShowHelper(string $ticket_id, Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'PCS'))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $ticket = Ticket::with('attributedUser')
                        ->with('attributedRole')
                        ->with('ticketCategory')
                        ->findOrFail($ticket_id);
    
    
        $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
        Log::info('updated', ['validate' => $ticket->formatted_date]);
                        

    return response()->json([
        'ticket' => $ticket
    ]);
}

public function apiUpdateHelper(string $ticket_id, Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'admin'))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $ticket = Ticket::findOrfail($ticket_id);
    $validateData = $request->validate([
        'status' => ['required', 'string'],
        'solution' => ['required', 'string']
    ]);
    Log::info('updated', ['request' => $request->all()]);
    Log::info('updated', ['validate' => $validateData]);
    $ticket->update($validateData);

}

public function apiIndexAdminHistory(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->with('roles')->first();
        if (!($user->roles->contains('nom', 'admin'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user_roles_id = [];
        foreach ($user->roles as $role){
           array_push($user_roles_id, $role->id);
        }
        

        $tickets = Ticket::query()
        ->with('attributedUser')
        ->with('attributedRole')
        ->with('ticketCategory')
        ->whereNot('attributed_user_id', null)
        ->get();
        
        
        foreach ($tickets as $ticket){
            $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
            Log::info('updated', ['validate' => $ticket->formatted_date]);
        }
                  
        
        return response()->json($tickets);
    }

    public function apiIndexPersonalHistory(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->with('roles')->first();
        if (!($user->roles->contains('nom', 'PCS'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user_roles_id = [];
        foreach ($user->roles as $role){
           array_push($user_roles_id, $role->id);
        }
        

        $tickets = Ticket::query()
        ->with('attributedUser')
        ->with('attributedRole')
        ->with('ticketCategory')
        ->where('attributed_user_id', $user->id)
        ->whereIn('status', ['Résolu', 'Rejeté'])
        ->get();
        
        Log::info('updated', ['validate' => $tickets]);
        
        foreach ($tickets as $ticket){
            $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
            Log::info('updated', ['validate' => $ticket->formatted_date]);
        }
        
        return response()->json($tickets);
    }

    public function apiStats(Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'PCS'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
    }

    $tickets = Ticket::query()
        ->with('attributedRole')
        ->with('ticketCategory')
        ->get();

    $role_counts = $tickets->groupBy(function($ticket) {
        return $ticket->attributedRole ? $ticket->attributedRole->nom : 'Non attribué'; //exclure les tickets non attribué
    })->map->count(); //utilisation de count de map pour avoir le compte pour chaque élément

    $status_counts = $tickets->groupBy('status')->map->count();
    Log::info('updated', ['status_counts' => $status_counts, 'role_counts' => $role_counts]);    

    return response()->json([
        'status_counts' => $status_counts,
        'role_counts' => $role_counts,
    ]);
}

}