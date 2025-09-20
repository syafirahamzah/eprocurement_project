<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['vendor_id', 'name', 'description', 'stock', 'price', 'unit'];

    public function vendor()
{
    return $this->belongsTo(User::class, 'vendor_id');
}

public function negotiations()
{
    return $this->hasMany(Negotiation::class);
}

public function orders()
{
    return $this->hasMany(Order::class);
}

}
