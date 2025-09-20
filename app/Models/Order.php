<?php

namespace App\Models;

use App\Models\Order;
use App\Models\AkadDokumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
   
   use HasFactory;

    public const STATUS_MENUNGGU    = 'menunggu konfirmasi';
    public const STATUS_DIPROSES    = 'diproses';
    public const STATUS_DIKIRIM     = 'dikirim';
    public const STATUS_SELESAI     = 'selesai';
    public const STATUS_DIBATALKAN  = 'dibatalkan';


    protected $fillable = [
        'negotiation_id', 'product_id', 'vendor_id', 'customer_id', 'agreed_price', 'status', 'quantity', 'payment_status', 'payment_method', 'agreement_document'

    ];

    public function negotiation()
{
    return $this->hasOne(Negotiation::class);
}


    public function product() {
        return $this->belongsTo(Product::class);
    }

     public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
{
    return $this->belongsTo(User::class, 'vendor_id');
}

public function tracking() {
    return $this->hasOne(Tracking::class);
}

public function akadDokumen()
{
    return $this->hasOne(AkadDokumen::class, 'order_id');
}

public function getDokumenSudahValidAttribute()
{
    return $this->payment_method === 'akad' &&
        $this->akadDokumen &&
        $this->akadDokumen->ktp_path &&
        $this->akadDokumen->agreement_path;
}

public function agreement()
{
    return $this->hasOne(Agreement::class, 'negotiation_id', 'negotiation_id');
}

public function fallbackAkadDokumen()
{
    return $this->hasOne(AkadDokumen::class, 'negotiation_id', 'negotiation_id');
}


}

