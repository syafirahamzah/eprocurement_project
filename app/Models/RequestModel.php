<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    // Jika nama tabel di database bukan 'request_models', ubah di sini:
    // protected $table = 'nama_tabel';

    // Jika ingin mengisi data massal (mass assignment), definisikan field yang boleh diisi:
    // protected $fillable = ['vendor_id', 'status', 'field_lainnya'];

    // Jika kamu tidak ingin timestamp otomatis (created_at, updated_at)
    // public $timestamps = false;

    public function product()
{
    return $this->belongsTo(Product::class);
}

public function customer()
{
    return $this->belongsTo(User::class, 'customer_id');
}

}
