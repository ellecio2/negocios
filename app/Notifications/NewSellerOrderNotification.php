<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewSellerOrderNotification extends Notification implements ShouldQueue {
    use Queueable;

    public $order_id;

    public function __construct(int $order_id) {
        $this->order_id = $order_id;
    }

    public function via($notifiable): array {
        return ['database'];
    }

    public function toDatabase($notifiable): array {
        $order = Order::find($this->order_id);

        return [
            'url' => route('orders.details', $this->order_id),
            'message' => 'Haz realizado una nueva venta. Orden. ' . $order->code,
        ];
    }

    public function toArray($notifiable): array {
        return [];
    }
}
