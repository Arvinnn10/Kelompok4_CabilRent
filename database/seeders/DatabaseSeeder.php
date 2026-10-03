<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Administrator CabilRent',
                'password'     => bcrypt('admin123'),
                'email'        => 'admin@cabilrent.com',
                'no_telp'      => '081234567890',
                'role'         => 'admin',
            ]
        );

        $customer = User::firstOrCreate(
            ['username' => 'customer'],
            [
                'nama_lengkap' => 'Customer Demo',
                'password'     => bcrypt('password'),
                'email'        => 'customer@cabilrent.com',
                'no_telp'      => '089876543210',
                'role'         => 'customers',
            ]
        );

        $catMobil = \App\Models\Category::firstOrCreate(['nama_kategori' => 'Mobil']);
        $catKebaya = \App\Models\Category::firstOrCreate(['nama_kategori' => 'Kebaya']);

        $prodMobil = \App\Models\Product::firstOrCreate(
            ['nama_produk' => 'Toyota Avanza 2023'],
            [
                'kategori_id'   => $catMobil->id,
                'desk_produk'   => 'Mobil MPV nyaman untuk keluarga kapasitas 7 penumpang.',
                'harga_sewa'    => 350000,
                'foto_produk'   => 'assets/illustration.png',
                'status_produk' => 'tersedia',
                'foto_kebaya'   => null,
            ]
        );

        $prodKebaya = \App\Models\Product::firstOrCreate(
            ['nama_produk' => 'Kebaya Modern Maroon'],
            [
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Kebaya pesta brokat mewah cocok untuk wisuda dan resepsi.',
                'harga_sewa'    => 150000,
                'foto_produk'   => 'assets/illustration.png',
                'status_produk' => 'tersedia',
                'foto_kebaya'   => null,
            ]
        );

        $basket = \App\Models\Basket::firstOrCreate(
            [
                'user_id'       => $customer->id,
                'produk_id'     => $prodKebaya->id,
                'tanggal_acara' => now()->addDays(7)->format('Y-m-d'),
            ],
            [
                'jumlah'        => 1,
            ]
        );

        \App\Models\Order::firstOrCreate(
            ['basket_id' => $basket->id],
            [
                'no_whatsapp'    => '089876543210',
                'instagram'      => '@customerdemo',
                'domisili'       => 'Banjarmasin',
                'desk_pemesanan' => 'Kebaya Maroon untuk Wisuda',
                'total_harga'    => 150000,
                'status_pesan'   => 'pending',
                'tb_bb'          => '160cm / 50kg',
            ]
        );
    }
}
