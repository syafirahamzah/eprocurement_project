<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\AkadDokumen;

class OrderObserver
{
    public function created(Order $order)
    {
        if ($order->payment_method === 'akad') {
            AkadDokumen::create([
                'order_id'       => $order->id,
                'negotiation_id' => $order->negotiation_id,
                'akad_file'      => $order->akad_document,
                'ktp_path'       => $order->ktp_file,
                'is_valid'       => false,
            ]);
        }
    }
}

