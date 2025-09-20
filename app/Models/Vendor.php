<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name', 'email', 'address', 'phone_number', 'category' // sesuaikan dengan kolom di tabel `vendors`
    ];
}
