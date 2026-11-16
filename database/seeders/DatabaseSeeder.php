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
        $catKebaya = Category::updateOrCreate(
            ['nama_kategori' => 'Paket Kebaya'],
            ['slug' => 'kebaya']
        );
        $catHeels = Category::updateOrCreate(
            ['nama_kategori' => 'Heels'],
            ['slug' => 'heels']
        );
        $catKamisol = Category::updateOrCreate(
            ['nama_kategori' => 'Kamisol'],
            ['slug' => 'kamisol']
        );

        // 3. Seed Products
        $productsData = [
            [
                'nama_produk'   => 'Coco Kebaya',
                'slug'          => 'kebaya-coco',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Inc : Rok, kamisol, selendang dan hijab.',
                'harga_sewa'    => 200000,
                'foto_produk'   => 'koleksi/kebaya-coco.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Ivory Palazzo Kebaya',
                'slug'          => 'kebaya-ivory-palazzo',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Inc : Rok, kamisol, selendang dan hijab.',
                'harga_sewa'    => 300000,
                'foto_produk'   => 'koleksi/kebaya-ivory-palazzo.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Aira Kebaya',
                'slug'          => 'kebaya-aira',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Inc : Rok, kamisol, selendang dan hijab.',
                'harga_sewa'    => 450000,
                'foto_produk'   => 'koleksi/kebaya-aira.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Ayana Kebaya',
                'slug'          => 'kebaya-ayana',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Inc : Rok, kamisol, selendang dan hijab.',
                'harga_sewa'    => 250000,
                'foto_produk'   => 'koleksi/kebaya-ayana.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Ayuna Kebaya (non hijab)',
                'slug'          => 'kebaya-ayuna',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Inc : Rok, kamisol, dan selendang.',
                'harga_sewa'    => 300000,
                'foto_produk'   => 'koleksi/kebaya-ayuna.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kiona Kebaya',
                'slug'          => 'kebaya-kiona',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Inc : Rok, kamisol, selendang dan hijab.',
                'harga_sewa'    => 350000,
                'foto_produk'   => 'koleksi/kebaya-kiona.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Meira Kebaya',
                'slug'          => 'kebaya-meira',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Inc : Rok, kamisol, selendang dan hijab.',
                'harga_sewa'    => 350000,
                'foto_produk'   => 'koleksi/kebaya-meira.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Arella Kebaya',
                'slug'          => 'kebaya-arella',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Inc : Rok, kamisol, dan hijab.',
                'harga_sewa'    => 350000,
                'foto_produk'   => 'koleksi/kebaya-arella.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Rose Kebaya',
                'slug'          => 'kebaya-rose',
                'kategori_id'   => $catKebaya->id,
                'desk_produk'   => 'Inc : Rok, kamisol, dan hijab.',
                'harga_sewa'    => 650000,
                'foto_produk'   => 'koleksi/kebaya-rose.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Arin Heels',
                'slug'          => 'heels-arin',
                'kategori_id'   => $catHeels->id,
                'desk_produk'   => 'Heels 5cm ukuran 37 cm',
                'harga_sewa'    => 50000,
                'foto_produk'   => 'koleksi/heels-arin.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Aqila Heels',
                'slug'          => 'heels-aqila',
                'kategori_id'   => $catHeels->id,
                'desk_produk'   => 'Heels 7cm ukuran 39 cm',
                'harga_sewa'    => 40000,
                'foto_produk'   => 'koleksi/heels-aqila.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Aura Heels',
                'slug'          => 'heels-aura',
                'kategori_id'   => $catHeels->id,
                'desk_produk'   => 'Heels 5cm ukuran 37 cm',
                'harga_sewa'    => 45000,
                'foto_produk'   => 'koleksi/heels-aura.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kayla Heels',
                'slug'          => 'heels-kayla',
                'kategori_id'   => $catHeels->id,
                'desk_produk'   => 'Heels 5cm ukuran 37 cm',
                'harga_sewa'    => 50000,
                'foto_produk'   => 'koleksi/heels-kayla.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kamisol Ukuran M',
                'slug'          => 'kamisol-m',
                'kategori_id'   => $catKamisol->id,
                'desk_produk'   => 'Kamisol ukuran M untuk dalaman kebaya.',
                'harga_sewa'    => 200000,
                'foto_produk'   => 'koleksi/kamisol-m.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kamisol Ukuran S',
                'slug'          => 'kamisol-s',
                'kategori_id'   => $catKamisol->id,
                'desk_produk'   => 'Kamisol ukuran S untuk dalaman kebaya.',
                'harga_sewa'    => 300000,
                'foto_produk'   => 'koleksi/kamisol-s.png',
                'status_produk' => 'tersedia',
            ],
            [
                'nama_produk'   => 'Kamisol Ukuran L',
                'slug'          => 'kamisol-l',
                'kategori_id'   => $catKamisol->id,
                'desk_produk'   => 'Kamisol ukuran L untuk dalaman kebaya.',
                'harga_sewa'    => 450000,
                'foto_produk'   => 'koleksi/kamisol-l.png',
                'status_produk' => 'tersedia',
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $data) {
            $createdProducts[] = Product::updateOrCreate(
                ['slug' => $data['slug']],
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
