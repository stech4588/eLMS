<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    public function markAsReadAndRedirect($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $link = '/';
        if(isset($notification->data['link'])){
            $link = $notification->data['link'];
        }
        $notification->markAsRead();
        return redirect($link);
    }
}
