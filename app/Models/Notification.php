<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
   protected $fillable = [
    'user_id',
    'title',
    'message',
    'product_id',
    'negotiation_id',
    'is_read',
    'type',
    'link_redirect',
];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function negotiation()
{
    return $this->belongsTo(Negotiation::class, 'negotiation_id');
}
}
