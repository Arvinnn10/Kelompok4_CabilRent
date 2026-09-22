<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'email',
        'no_telp',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relasi: satu user punya banyak keranjang
    public function baskets()
    {
        return $this->hasMany(Basket::class, 'user_id');
    }

    // Relasi: satu user punya banyak pesanan
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    // Helper: cek apakah user adalah admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Helper: cek apakah user adalah customer
    public function isCustomer(): bool
    {
        return $this->role === 'customers';
    }
}
