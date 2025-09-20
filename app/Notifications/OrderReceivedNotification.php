<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class OrderReceivedNotification extends Notification
{
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }
//ini tempat notifikasi yang dikirim ke vendor kalo customer konfirmasi pengiriman
    public function via($notifiable)
    {
        return ['database']; // Notifikasi disimpan dalam database
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Pesanan ' . $this->order->id . ' telah diterima oleh customer.',
            'order_id' => $this->order->id,
        ];
    }
};
