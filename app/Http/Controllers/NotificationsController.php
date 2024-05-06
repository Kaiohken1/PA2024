<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Afficher les notifications de l'utilisateur connecté.
     */
    public function index(User $user)
    {
        $currentUser = User::findOrFail(Auth::user()->id); // Renommez la variable pour éviter la confusion
        $notifications = $currentUser->notifications()->latest()->paginate(10);
    
        return view('layouts.navigation', ['notifications' => $notifications]);    
    }
    

    /**
     * Marquer une notification comme lue.
     */
    public function markAsRead(User $user, $notificationId)
    {
        $notification = $user->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return redirect()->back();
    }

    /**
     * Supprimer une notification.
     */
    public function destroy(User $user, $notificationId)
    {
        $notification = $user->notifications()->findOrFail($notificationId);
        $notification->delete();

        return redirect()->back();
    }
}
