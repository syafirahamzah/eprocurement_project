<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkadDokumen extends Model
{
    protected $table = 'akad_dokumens';

    protected $fillable = [
    'order_id',
    'negotiation_id', 
    'ktp_path',
    'akad_file',
    'is_valid',
];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function negotiation()
{
    return $this->belongsTo(Negotiation::class);
}

}
