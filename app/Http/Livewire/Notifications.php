<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Notifications extends Component
{

    public $count = 10;
    //protected $listeners = ['handleNotification'];
    protected $listeners = ['notificationReceived', 'notificationsMarkedAsRead'];

    public function getListeners()
    {
        return [
            'notificationReceived' => 'notificationReceived',
            'notificationsMarkedAsRead' => 'render',
        ];
    }

    public function readNotification($id)
    {
        auth()->user()->notifications->find($id)->markAsRead();
    }

    public function markAllAsRead()
    {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
            $this->emit('notificationsMarkedAsRead');
        }
    }

    public function notificationReceived($order)
    {
        //log::info('llega notificaciónes cliente' . json_encode($order, true));
        $this->render();
    }

    public function render()
    {
        #return view('livewire.notifications');
        return view('livewire.notifications', [
            'notifications' => $this->getNotificationsProperty(),
        ]);
    }

    public function getNotificationsProperty()
    {
        return auth()->user()->notifications->take($this->count);
    }
}
