<?php

namespace App\Http\Controllers\Workshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkshopNotificationController extends Controller
{
    public function mark_all_notifications(){
        auth()->user()->unreadNotifications->markAsRead();
        return  redirect()->route('workshop.workshopService.index');
    }

    public function mark_a_notification($notification_id){
        auth()->user()->unreadNotifications->when($notification_id, function($query) use($notification_id){
            return $query->where('id', $notification_id); 
        })->markAsRead();
        return  redirect()->route('workshop.workshopService.index');
    }
}
