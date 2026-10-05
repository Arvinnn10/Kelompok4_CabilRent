<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Basket;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed / Ensure Default Admin & Customer
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Administrator CabilRent',
                'password'     => Hash::make('admin123'),
                'email'        => 'admin@cabilrent.com',
                'no_telp'      => '081234567890',
                'role'         => 'admin',
            ]
        );

        $customer = User::firstOrCreate(
            ['username' => 'customer'],
            [
                'nama_lengkap' => 'Customer Demo',
                'password'     => Hash::make('password'),
                'email'        => 'customer@cabilrent.com',
                'no_telp'      => '089876543210',
                'role'         => 'customers',
            ]
        );

        // 2. Seed Categories
        $catKebaya = Category::firstOrCreate(['nama_kategori' => 'Paket Kebaya']);
        $catHeels  = Category::firstOrCreate(['nama_kategori' => 'Heels']);
        $catKemben = Category::firstOrCreate(['nama_kategori' => 'Kemben']);
        $catAdat   = Category::firstOrCreate(['nama_kategori' => 'Adat']);

        // 3. Seed Products
        $productsData = [
            [
                'nama_produk'   => 'Kebaya Brokat Maroon',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Kebaya brokat maroon elegan dengan detail payet cantik, cocok untuk wisuda dan kondangan.',
                'harga_sewa'    => 250000,
                'foto_produk'   => 'produk-brokat-maroon.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kebaya Tulle Sage Green',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Kebaya modern warna sage green berbahan tulle premium dengan aksen bordir bunga.',
                'harga_sewa'    => 275000,
                'foto_produk'   => 'produk-tulle-sage.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kebaya Modern Navy',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Kebaya navy bernuansa modern dan mewah, sangat anggun untuk acara formal malam hari.',
                'harga_sewa'    => 280000,
                'foto_produk'   => 'produk-modern-navy.png',
                'status_produk' => 'tidak_tersedia',
            ],
            [
                'nama_produk'   => 'Kebaya Putih Gading',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Kebaya putih gading klasik dengan sentuhan mutiara, sempurna untuk akad dan lamaran.',
                'harga_sewa'    => 300000,
                'foto_produk'   => 'produk-putih-gading.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kebaya Rose Dusty',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Kebaya warna dusty rose bernuansa manis dan feminin dengan bahan tile halus.',
                'harga_sewa'    => 260000,
                'foto_produk'   => 'produk-rose-dusty.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kebaya Encim Gold Klasik',
                'kategori_id'   => $catAdat->id,
                'desk_produk'   => 'Kebaya encim tradisional warna keemasan dengan motif bordir halus khas nusantara.',
                'harga_sewa'    => 290000,
                'foto_produk'   => 'produk-encim-gold.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kemben Satin Premium',
                'kategori_id'   => $catKemben->id,
                'desk_produk'   => 'Kemben satin halus dan nyaman sebagai dalaman kebaya atau busana tradisional.',
                'harga_sewa'    => 50000,
                'foto_produk'   => 'kemben.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Heels Pesta Pearl White',
                'kategori_id'   => $catHeels->id,
                'desk_produk'   => 'Sepatu heels pesta berhias manik mutiara cantik, tinggi hak 5cm nyaman dipakai.',
                'harga_sewa'    => 85000,
                'foto_produk'   => 'kategori-heels.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Heels Pesta Glitter Gold',
                'kategori_id'   => $catHeels->id,
                'desk_produk'   => 'Heels elegan bertabur glitter keemasan untuk melengkapi busana kebaya pesta.',
                'harga_sewa'    => 90000,
                'foto_produk'   => 'sepatu1.png',
                'status_produk' => 'tersedia',
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $data) {
            $createdProducts[] = Product::firstOrCreate(
                ['nama_produk' => $data['nama_produk']],
                $data
            );
        }

        // 4. Seed Sample Baskets & Orders
        if (isset($createdProducts[0]) && isset($createdProducts[1])) {
            $basket1 = Basket::firstOrCreate(
                [
                    'user_id'       => $customer->id,
                    'produk_id'     => $createdProducts[0]->id,
                    'tanggal_acara' => now()->addDays(3)->format('Y-m-d'),
                ],
                ['jumlah' => 1]
            );

            Order::firstOrCreate(
                ['basket_id' => $basket1->id],
                [
                    'no_whatsapp'    => '081234567891',
                    'instagram'      => '@haileybieber',
                    'domisili'       => 'Banjarmasin',
                    'desk_pemesanan' => 'Kebaya Brokat Maroon (M) untuk Wisuda',
                    'total_harga'    => 250000,
                    'status_pesan'   => 'dikonfirmasi',
                    'tb_bb'          => '162cm / 48kg',
                ]
            );

            $basket2 = Basket::firstOrCreate(
                [
                    'user_id'       => $customer->id,
                    'produk_id'     => $createdProducts[1]->id,
                    'tanggal_acara' => now()->addDays(5)->format('Y-m-d'),
                ],
                ['jumlah' => 1]
            );

            Order::firstOrCreate(
                ['basket_id' => $basket2->id],
                [
                    'no_whatsapp'    => '089876543210',
                    'instagram'      => '@billieeilis',
                    'domisili'       => 'Banjarbaru',
                    'desk_pemesanan' => 'Kebaya Tulle Sage Green untuk Acara Lamaran',
                    'total_harga'    => 275000,
                    'status_pesan'   => 'pending',
                    'tb_bb'          => '158cm / 50kg',
                ]
            );
        }
    }
}
