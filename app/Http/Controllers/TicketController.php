<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\TicketMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use MBarlow\Megaphone\Types\General;
use SebastianBergmann\CodeUnit\FunctionUnit;

class TicketController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $tickets = Ticket::query()->with(['attributedRole'])
                       ->where('asker_user_id', $user_id)
                       ->get();
                       

        return view('ticket.index', [
            'tickets' => $tickets
        ]);  

        
    }

    public function create()
    {
        $roles = Role::where('id', '>', 5)->get();

        return view('ticket.create', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attributed_role_id' => 'required|numeric'
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
        $roles = Role::all();
        $ticket = Ticket::findOrFail($id);
        

        return view('ticket.edit', [
            'roles' => $roles,
            'ticket' => $ticket
        ]);  

    }

    public function update(string $id, Request $request)
    {
        $ticket = Ticket::findOrFail($id);
        $validateData = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attributed_role_id' => 'required|numeric'
        ]);


        if ($ticket->attributed_user_id == null){
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

        if ($ticket->attributed_user_id == null){
            $ticket->delete();
            return redirect()->route('tickets.index')
            ->with('status', 'Ticket supprimé avec succès');
        } else{
            return redirect()->route('tickets.index')
        ->with('status', 'La suppression du ticket n\'est plus possible');
        }


        
        

        

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
        'solution' => ['string'],
        'priority' => ['required', 'numeric']
    ]);
    Log::info('updated', ['request' => $request->all()]);
    Log::info('updated', ['validate' => $validateData]);
    $ticket->update($validateData);

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

public function apiIndexAdmin(Request $request)
    {
        $user = $request->user();
        $user = User::where('id', $user->id)->with('roles')->first();
        if (!($user->roles->contains('nom', 'PCS') and ($user->roles->contains('nom', 'admin')))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $tickets = Ticket::query()
        ->with('attributedUser')
        ->with('attributedRole')
        ->get();

        foreach ($tickets as $ticket){
            $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
            Log::info('updated', ['validate' => $ticket->formatted_date]);
        }
                            
        
        return response()->json($tickets);
    }

    public function apiShowAdmin(string $ticket_id, Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'PCS') and ($user->roles->contains('nom', 'admin')))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    

    $ticket = Ticket::with('attributedUser')
                        ->with('attributedRole')
                        ->findOrFail($ticket_id);
                        
$users = User::with('roles')
        ->whereHas('roles', function($query) use ($ticket) {
        $query->where('roles.id', $ticket->attributed_role_id);
    })->get();
    Log::info('updated', ['validate' => $users]);
    
    $ticket->formatted_date = Carbon::parse($ticket->created_at)->format('d/m/Y');
    Log::info('updated', ['validate' => $ticket->formatted_date]);

    return response()->json([
        'ticket' => $ticket,
        'users' => $users
    ]);
}

public function apiUpdateAdmin(string $ticket_id, Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'PCS') and ($user->roles->contains('nom', 'admin')))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $ticket = Ticket::findOrfail($ticket_id);
    $validateData = $request->validate([
        'attributed_user_id' => ['required', 'numeric']
    ]);


    Log::info('updated', ['request' => $request->all()]);
    Log::info('updated', ['user' => $user]);
    $ticket->update($validateData);

}

public function apiTicketChat(string $ticket_id, Request $request)
{
    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    if (!($user->roles->contains('nom', 'PCS') and ($user->roles->contains('nom', 'admin')))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $messages = TicketMessage::query()
                ->where('ticket_id', $ticket_id)
                ->with('fromUser')
                ->with('toUser') //->with('toUser:id,first_name,name,email,roles')
                ->get();
    
    foreach ($messages as $message){
            $message->formatted_date = Carbon::parse($message->created_at)->format('d/m/Y');

        }

    Log::info('Fetch messages', ['messages' => $messages, 'ticket_id' => $ticket_id]);

    return response()->json([
        'messages' => $messages
    ]);

}

public function apiTicketChatSend(Request $request)
{

    
    $validateData = $request->validate([
        'ticket_id' => ['required', 'numeric'],
        'message' => ['required', 'string']
    ]);

    $user = $request->user();
    $user = User::where('id', $user->id)->with('roles')->first();
    

    if (!($user->roles->contains('nom', 'PCS'))) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $ticket = Ticket::findOrfail($validateData['ticket_id']);
    
    $message = new TicketMessage($validateData);
    $message->from_user_id = $ticket->attributed_user_id;
    $message->to_user_id = $ticket->asker_user_id;

    $message->save();

    $notification = new General(
        'Nouveau message - Ticket #' . $ticket->asker_user_id,
        'Un nouveau message a été envoyé',
        url('/tickets/' . $ticket->asker_user_id . '/chat/' . $ticket->asker_user_id),
        'Voir la conversation'
    );

    $toUser = User::findOrFail($ticket->asker_user_id);
    $toUser->notify($notification);

}
}