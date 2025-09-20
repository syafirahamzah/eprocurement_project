<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegotiationMessage extends Model
{
    use HasFactory;

    protected $fillable = ['negotiation_id', 'message', 'sender_role', 'is_read'];

    public function negotiation()
{
    return $this->belongsTo(Negotiation::class);
}
}
