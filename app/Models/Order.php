<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'basket_id',
        'no_whatsapp',
        'instagram',
        'domisili',
        'desk_pemesanan',
        'total_harga',
        'status_pesan',
        'tb_bb',
    ];

    protected $casts = [
        'total_harga' => 'integer',
    ];

    // Relasi: pesanan milik satu keranjang (basket)
    public function basket()
    {
        return $this->belongsTo(Basket::class, 'basket_id');
    }

    // Relasi: user dari pesanan melalui basket
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Basket::class,
            'id',
            'id',
            'basket_id',
            'user_id'
        );
    }

    // Relasi: produk dari pesanan melalui basket
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            Basket::class,
            'id',
            'id',
            'basket_id',
            'produk_id'
        );
    }
}