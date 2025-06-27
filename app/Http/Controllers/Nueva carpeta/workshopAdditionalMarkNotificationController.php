<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class workshopAdditionalMarkNotificationController extends Controller
{
    public function mark_all_additional_notifications(){
        auth()->user()->unreadNotifications->markAsRead();
        return  redirect()->route('frontend.user.workshop_request.index');
    }

    public function mark_a_additional_notification($notification_id){
        auth()->user()->unreadNotifications->when($notification_id, function($query) use($notification_id){
            return $query->where('id', $notification_id); 
        })->markAsRead();
        return  redirect()->route('frontend.user.workshop_request.index');
    }
}
