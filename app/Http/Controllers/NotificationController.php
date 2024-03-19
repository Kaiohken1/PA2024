<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function showNotifications()
    {
    $notifications = Auth::user()->notifications;

    return view('admin.index')->with('notifications', $notifications);
    }


    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

    }
}
