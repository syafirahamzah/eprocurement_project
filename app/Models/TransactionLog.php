<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $fillable = [
        'order_id', 'action', 'performed_by', 'performed_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
