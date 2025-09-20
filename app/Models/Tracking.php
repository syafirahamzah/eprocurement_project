<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{



    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_DIKIRIM  = 'dikirim';
    public const STATUS_SELESAI  = 'selesai';


protected $table = 'trackings';

    protected $fillable = [
        'order_id',
        'status',
        'updated_at_status',
        'estimated_delivery',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
   
public static function createTracking($orderId)
{
    return self::create([
        'order_id' => $orderId,  // Pastikan order_id valid
        'status' => 'diproses',
        'estimated_delivery' => '2025-07-11 18:47:57',
    ]);
}

}
