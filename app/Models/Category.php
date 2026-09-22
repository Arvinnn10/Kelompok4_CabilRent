<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'nama_kategori',
    ];

    // Relasi: satu kategori punya banyak produk
    public function products()
    {
        return $this->hasMany(Product::class, 'kategori_id');
    }
}
