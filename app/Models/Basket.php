<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $fillable = [
        'user_id',
        'produk_id',
        'jumlah',
        'tanggal_acara',
    ];

    protected $casts = [
        'jumlah'        => 'integer',
        'tanggal_acara' => 'date',
        'created_at'    => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($basket) {
            if ($basket->jumlah !== null && (int)$basket->jumlah < 1) {
                throw new \InvalidArgumentException('Jumlah keranjang minimal 1 dan tidak boleh 0 atau negatif.');
            }
        });
    }

    // Relasi: keranjang milik satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: keranjang berisi satu produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    // Relasi: keranjang memiliki pesanan
    public function orders()
    {
        return $this->hasMany(Order::class, 'basket_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'basket_id');
    }
}
