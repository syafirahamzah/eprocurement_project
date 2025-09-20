<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    // Mass assignment
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'role',
        'category',
    ];

    // Disembunyikan saat serialisasi
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast otomatis
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke produk untuk vendor
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    // Relasi ke notifikasi
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function orders()
{
    return $this->hasMany(\App\Models\Order::class);
}

public function hasRole($role)
{
    return $this->role === $role;
}

}
