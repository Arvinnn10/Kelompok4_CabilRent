<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        $dbName = DB::getDatabaseName();

        if ($driver === 'mysql') {
            // 1. Table users: status default 'aktif'
            if (Schema::hasTable('users') && Schema::hasColumn('users', 'status')) {
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `status` ENUM('aktif', 'diblokir') NOT NULL DEFAULT 'aktif'");
            }

            // 2. Table categories: unique slug
            if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'slug')) {
                $catSlugUnique = DB::select("
                    SELECT INDEX_NAME 
                    FROM information_schema.STATISTICS 
                    WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'categories' AND COLUMN_NAME = 'slug' AND NON_UNIQUE = 0
                ", [$dbName]);

                if (empty($catSlugUnique)) {
                    Schema::table('categories', function (Blueprint $table) {
                        $table->unique('slug', 'categories_slug_unique');
                    });
                }
            }

            // 3. Table products: unique slug, unsigned int stok default 1, FK kategori_id restrict
            if (Schema::hasTable('products')) {
                // Slug unique
                if (Schema::hasColumn('products', 'slug')) {
                    $prodSlugUnique = DB::select("
                        SELECT INDEX_NAME 
                        FROM information_schema.STATISTICS 
                        WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'products' AND COLUMN_NAME = 'slug' AND NON_UNIQUE = 0
                    ", [$dbName]);

                    if (empty($prodSlugUnique)) {
                        Schema::table('products', function (Blueprint $table) {
                            $table->unique('slug', 'products_slug_unique');
                        });
                    }
                }

                // Stok unsigned int default 1
                if (Schema::hasColumn('products', 'stok')) {
                    DB::statement("ALTER TABLE `products` MODIFY COLUMN `stok` INT UNSIGNED NOT NULL DEFAULT 1");
                }

                // FK kategori_id -> categories(id) ON DELETE RESTRICT
                if (Schema::hasColumn('products', 'kategori_id')) {
                    DB::statement("ALTER TABLE `products` MODIFY COLUMN `kategori_id` BIGINT UNSIGNED NOT NULL");

                    $existingFk = DB::select("
                        SELECT CONSTRAINT_NAME, DELETE_RULE 
                        FROM information_schema.REFERENTIAL_CONSTRAINTS 
                        WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = 'products' AND REFERENCED_TABLE_NAME = 'categories'
                    ", [$dbName]);

                    foreach ($existingFk as $fk) {
                        if (strtoupper($fk->DELETE_RULE) !== 'RESTRICT' && strtoupper($fk->DELETE_RULE) !== 'NO ACTION') {
                            DB::statement("ALTER TABLE `products` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                            $existingFk = [];
                            break;
                        }
                    }

                    if (empty($existingFk)) {
                        Schema::table('products', function (Blueprint $table) {
                            $table->foreign('kategori_id', 'products_kategori_id_foreign')
                                  ->references('id')
                                  ->on('categories')
                                  ->restrictOnDelete();
                        });
                    }
                }

                // Check constraint stok >= 0
                $stokCheck = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.TABLE_CONSTRAINTS 
                    WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'products' AND CONSTRAINT_NAME = 'chk_products_stok'
                ", [$dbName]);
                if (empty($stokCheck)) {
                    try {
                        DB::statement("ALTER TABLE `products` ADD CONSTRAINT `chk_products_stok` CHECK (`stok` >= 0)");
                    } catch (\Throwable $e) {}
                }
            }

            // 4. Table baskets: jumlah unsigned int default 1, FK user_id cascade, FK produk_id cascade, check constraint jumlah >= 1
            if (Schema::hasTable('baskets')) {
                if (Schema::hasColumn('baskets', 'jumlah')) {
                    DB::statement("ALTER TABLE `baskets` MODIFY COLUMN `jumlah` INT UNSIGNED NOT NULL DEFAULT 1");
                }

                if (Schema::hasColumn('baskets', 'user_id')) {
                    DB::statement("ALTER TABLE `baskets` MODIFY COLUMN `user_id` BIGINT UNSIGNED NOT NULL");

                    $userFk = DB::select("
                        SELECT CONSTRAINT_NAME, DELETE_RULE 
                        FROM information_schema.REFERENTIAL_CONSTRAINTS 
                        WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = 'baskets' AND REFERENCED_TABLE_NAME = 'users'
                    ", [$dbName]);

                    foreach ($userFk as $fk) {
                        if (strtoupper($fk->DELETE_RULE) !== 'CASCADE') {
                            DB::statement("ALTER TABLE `baskets` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                            $userFk = [];
                            break;
                        }
                    }

                    if (empty($userFk)) {
                        Schema::table('baskets', function (Blueprint $table) {
                            $table->foreign('user_id', 'baskets_user_id_foreign')
                                  ->references('id')
                                  ->on('users')
                                  ->cascadeOnDelete();
                        });
                    }
                }

                if (Schema::hasColumn('baskets', 'produk_id')) {
                    DB::statement("ALTER TABLE `baskets` MODIFY COLUMN `produk_id` BIGINT UNSIGNED NOT NULL");

                    $prodFk = DB::select("
                        SELECT CONSTRAINT_NAME, DELETE_RULE 
                        FROM information_schema.REFERENTIAL_CONSTRAINTS 
                        WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = 'baskets' AND REFERENCED_TABLE_NAME = 'products'
                    ", [$dbName]);

                    foreach ($prodFk as $fk) {
                        if (strtoupper($fk->DELETE_RULE) !== 'CASCADE') {
                            DB::statement("ALTER TABLE `baskets` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                            $prodFk = [];
                            break;
                        }
                    }

                    if (empty($prodFk)) {
                        Schema::table('baskets', function (Blueprint $table) {
                            $table->foreign('produk_id', 'baskets_produk_id_foreign')
                                  ->references('id')
                                  ->on('products')
                                  ->cascadeOnDelete();
                        });
                    }
                }

                // Check constraint jumlah >= 1
                $jumlahCheck = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.TABLE_CONSTRAINTS 
                    WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'baskets' AND CONSTRAINT_NAME = 'chk_baskets_jumlah'
                ", [$dbName]);
                if (empty($jumlahCheck)) {
                    try {
                        DB::statement("ALTER TABLE `baskets` ADD CONSTRAINT `chk_baskets_jumlah` CHECK (`jumlah` >= 1)");
                    } catch (\Throwable $e) {}
                }
            }

            // 5. Table orders: status_pesan default 'pending', FK basket_id restrict
            if (Schema::hasTable('orders')) {
                if (Schema::hasColumn('orders', 'status_pesan')) {
                    DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status_pesan` ENUM('pending', 'dikonfirmasi', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'pending'");
                }

                if (Schema::hasColumn('orders', 'basket_id')) {
                    DB::statement("ALTER TABLE `orders` MODIFY COLUMN `basket_id` BIGINT UNSIGNED NOT NULL");

                    $basketFk = DB::select("
                        SELECT CONSTRAINT_NAME, DELETE_RULE 
                        FROM information_schema.REFERENTIAL_CONSTRAINTS 
                        WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = 'orders' AND REFERENCED_TABLE_NAME = 'baskets'
                    ", [$dbName]);

                    foreach ($basketFk as $fk) {
                        if (strtoupper($fk->DELETE_RULE) !== 'RESTRICT' && strtoupper($fk->DELETE_RULE) !== 'NO ACTION') {
                            DB::statement("ALTER TABLE `orders` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                            $basketFk = [];
                            break;
                        }
                    }

                    if (empty($basketFk)) {
                        Schema::table('orders', function (Blueprint $table) {
                            $table->foreign('basket_id', 'orders_basket_id_foreign')
                                  ->references('id')
                                  ->on('baskets')
                                  ->restrictOnDelete();
                        });
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $dbName = DB::getDatabaseName();

        // Rollback check constraints if any
        try {
            DB::statement("ALTER TABLE `baskets` DROP CHECK `chk_baskets_jumlah`");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE `products` DROP CHECK `chk_products_stok`");
        } catch (\Throwable $e) {}
    }
};
