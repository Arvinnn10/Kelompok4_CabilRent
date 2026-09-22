<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'produk_id',
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

    // Relasi: pesanan milik satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: pesanan berisi satu produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }
}
