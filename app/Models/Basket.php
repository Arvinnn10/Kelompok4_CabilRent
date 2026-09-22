<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    public $timestamps = false; // hanya ada created_at, tidak ada updated_at

    protected $fillable = [
        'user_id',
        'produk_id',
        'jumlah',
        'tanggal_acara',
    ];

    protected $casts = [
        'tanggal_acara' => 'date',
        'created_at'    => 'datetime',
    ];

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
}
