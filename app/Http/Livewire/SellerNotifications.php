<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SellerNotifications extends Component
{

    public $is_linkable = false;
    public int $count = 20;
    public bool $newNotification = false;

    protected $listeners = ['notificationReceived', 'notificationsMarkedAsRead'];

    public function getListeners()
    {
        return [
            'notificationReceived' => 'notificationReceived',
            'notificationsMarkedAsRead' => 'render',
        ];
    }

    public function notificationReceived($order)
    {
        $notifications = $this->getNotificationsProperty();
        $filteredData = $notifications->filter(function ($item) {
            return $item->data['status'] === 'confirmed';
        });
        $count = $filteredData->count();
        $this->newNotification = true;
        //$this->emit('newNotification', $count);
        $this->emit('newNotification', $count, $filteredData);
        $this->render();
    }

    public function getNotificationsProperty()
    {

        return auth()->user()->unreadNotifications()->where('type', 'App\Notifications\OrderNotification')->latest()->take($this->count)->get();
        /* return Order::where('seller_id', auth()->id())
         ->where('viewed', 0)
         ->latest()
         ->take($this->count)
         ->get(); */

        /*$order_query = Order::query(); // Crear la consulta base
        $order_query->where('seller_id', auth()->id());
        $order_query->where('viewed', 0);
        $order_query->latest();
        $order_query->take($this->count);
        return $order_query->get();*/

    }

    public function render()
    {
        $notifications = $this->getNotificationsProperty();
        $filteredData = $notifications->filter(function ($item) {
            return $item->data['status'] === 'confirmed';
        });
        /*$count = $filteredData->count();
        dd($notifications, $count);*/
        $orders = Order::where('seller_id', auth()->user()->id)
            ->where('viewed', 0)
            ->where('payment_status', 'paid')
            ->get();

        //$count = $filteredData->count();
        $count = $orders->count();
        //dd(auth()->user()->id);
        $this->newNotification = false;
        return view('livewire.seller-notifications', [
            'notifications' => $filteredData,
            'notificationsCount' => $count,
        ]);

    }

    /*public function render()
    {
        log::info('llega notificaciónes render');
        $this->newNotification = false;
        return view('livewire.seller-notifications');
    }*/

    public function markAllAsRead()
    {
        log::info('markAllAsRead');
        auth()->user()->unreadNotifications()->where('type', 'App\Notifications\OrderNotification')->get()->each(function ($notification) {
            $notification->markAsRead();
        });

        $this->emit('notificationsMarkedAsRead');
    }

    /*public function getListeners() {
        return [
            "echo-notification:App.Models.User.".auth()->id().",notification" => 'render',
            'notificationsMarkedAsRead' => 'render'
        ];
    }*/
}
