<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Negotiation extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'product_id',
        'vendor_id',
        'customer_id',
        'quantity',
        'proposed_price',
        'offered_price',
        'final_price',
        'status',
        'order_id',
        'is_approved',
    ];

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke customer (user)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }


public function order()
{
    return $this->hasOne(Order::class, 'negotiation_id');
}

public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    // Relasi ke vendor (user)
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }


    // Relasi ke pesan-pesan negosiasi
    public function messages()
    {
        return $this->hasMany(NegotiationMessage::class);
    }
}
