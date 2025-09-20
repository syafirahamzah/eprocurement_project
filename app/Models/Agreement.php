<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
      protected $fillable = [
        'user_id',
        'negotiation_id',
        'agreement_text',
        'ip_address',
        'agreed_at',
        'agreement_file',
        'ktp_file',
    ];
}
