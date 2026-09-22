<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'desk_produk',
        'harga_sewa',
        'foto_produk',
        'status_produk',
        'foto_kebaya',
    ];

    // Relasi: produk milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    // Relasi: produk ada di banyak keranjang
    public function baskets()
    {
        return $this->hasMany(Basket::class, 'produk_id');
    }

    // Relasi: produk ada di banyak pesanan
    public function orders()
    {
        return $this->hasMany(Order::class, 'produk_id');
    }
}
