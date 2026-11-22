<?php

use Illuminate\Database\Migrations\Migration;
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
        if ($driver !== 'mysql') {
            return;
        }

        $dbName = DB::getDatabaseName();

        // 1. Table users: status default 'aktif'
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'status')) {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `status` ENUM('aktif', 'diblokir') NOT NULL DEFAULT 'aktif'");
        }

        // 2. Table categories: unique slug
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'slug')) {
            $this->ensureUniqueIndex($dbName, 'categories', 'slug', 'categories_slug_unique');
        }

        // 3. Table products: unique slug, unsigned int stok default 1, FK kategori_id restrict, check constraint stok >= 0
        if (Schema::hasTable('products')) {
            if (Schema::hasColumn('products', 'slug')) {
                $this->ensureUniqueIndex($dbName, 'products', 'slug', 'products_slug_unique');
            }

            if (Schema::hasColumn('products', 'stok')) {
                DB::statement("ALTER TABLE `products` MODIFY COLUMN `stok` INT UNSIGNED NOT NULL DEFAULT 1");
            }

            if (Schema::hasColumn('products', 'kategori_id')) {
                DB::statement("ALTER TABLE `products` MODIFY COLUMN `kategori_id` BIGINT UNSIGNED NOT NULL");
                $this->ensureForeignKey(
                    $dbName,
                    table: 'products',
                    column: 'kategori_id',
                    refTable: 'categories',
                    refColumn: 'id',
                    rule: 'RESTRICT',
                    constraintName: 'products_kategori_id_foreign'
                );
            }

            $this->ensureCheckConstraint($dbName, 'products', 'chk_products_stok', '`stok` >= 0');
        }

        // 4. Table baskets: jumlah unsigned int default 1, FK user_id cascade, FK produk_id cascade, check constraint jumlah >= 1
        if (Schema::hasTable('baskets')) {
            if (Schema::hasColumn('baskets', 'jumlah')) {
                DB::statement("ALTER TABLE `baskets` MODIFY COLUMN `jumlah` INT UNSIGNED NOT NULL DEFAULT 1");
            }

            if (Schema::hasColumn('baskets', 'user_id')) {
                DB::statement("ALTER TABLE `baskets` MODIFY COLUMN `user_id` BIGINT UNSIGNED NOT NULL");
                $this->ensureForeignKey(
                    $dbName,
                    table: 'baskets',
                    column: 'user_id',
                    refTable: 'users',
                    refColumn: 'id',
                    rule: 'CASCADE',
                    constraintName: 'baskets_user_id_foreign'
                );
            }

            if (Schema::hasColumn('baskets', 'produk_id')) {
                DB::statement("ALTER TABLE `baskets` MODIFY COLUMN `produk_id` BIGINT UNSIGNED NOT NULL");
                $this->ensureForeignKey(
                    $dbName,
                    table: 'baskets',
                    column: 'produk_id',
                    refTable: 'products',
                    refColumn: 'id',
                    rule: 'CASCADE',
                    constraintName: 'baskets_produk_id_foreign'
                );
            }

            $this->ensureCheckConstraint($dbName, 'baskets', 'chk_baskets_jumlah', '`jumlah` >= 1');
        }

        // 5. Table orders: status_pesan default 'pending', FK basket_id restrict
        if (Schema::hasTable('orders')) {
            if (Schema::hasColumn('orders', 'status_pesan')) {
                DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status_pesan` ENUM('pending', 'dikonfirmasi', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'pending'");
            }

            if (Schema::hasColumn('orders', 'basket_id')) {
                DB::statement("ALTER TABLE `orders` MODIFY COLUMN `basket_id` BIGINT UNSIGNED NOT NULL");
                $this->ensureForeignKey(
                    $dbName,
                    table: 'orders',
                    column: 'basket_id',
                    refTable: 'baskets',
                    refColumn: 'id',
                    rule: 'RESTRICT',
                    constraintName: 'orders_basket_id_foreign'
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver !== 'mysql') {
            return;
        }

        $dbName = DB::getDatabaseName();

        // 1. Rollback CHECK constraints
        $this->dropCheckConstraintIfExists($dbName, 'baskets', 'chk_baskets_jumlah');
        $this->dropCheckConstraintIfExists($dbName, 'products', 'chk_products_stok');

        // 2. Rollback Foreign Keys to original CASCADE state
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'basket_id')) {
            $this->ensureForeignKey(
                $dbName,
                table: 'orders',
                column: 'basket_id',
                refTable: 'baskets',
                refColumn: 'id',
                rule: 'CASCADE',
                constraintName: 'orders_basket_id_foreign'
            );
        }

        if (Schema::hasTable('products') && Schema::hasColumn('products', 'kategori_id')) {
            $this->ensureForeignKey(
                $dbName,
                table: 'products',
                column: 'kategori_id',
                refTable: 'categories',
                refColumn: 'id',
                rule: 'CASCADE',
                constraintName: 'products_kategori_id_foreign'
            );
        }

        // 3. Rollback UNIQUE constraints on slugs
        $this->dropUniqueIndexIfExists($dbName, 'categories', 'categories_slug_unique');
        $this->dropUniqueIndexIfExists($dbName, 'products', 'products_slug_unique');

        // 4. Rollback data types from unsigned back to standard integer
        if (Schema::hasTable('baskets') && Schema::hasColumn('baskets', 'jumlah')) {
            DB::statement("ALTER TABLE `baskets` MODIFY COLUMN `jumlah` INT NOT NULL DEFAULT 1");
        }

        if (Schema::hasTable('products') && Schema::hasColumn('products', 'stok')) {
            DB::statement("ALTER TABLE `products` MODIFY COLUMN `stok` INT NOT NULL DEFAULT 1");
        }
    }

    /**
     * Get foreign key info by source table & column AND referenced table & column.
     */
    private function getForeignKeyInfo(string $dbName, string $table, string $column, string $refTable, string $refColumn): ?object
    {
        $res = DB::select('
            SELECT kcu.CONSTRAINT_NAME, rc.DELETE_RULE
            FROM information_schema.KEY_COLUMN_USAGE kcu
            JOIN information_schema.REFERENTIAL_CONSTRAINTS rc 
                ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME 
                AND kcu.CONSTRAINT_SCHEMA = rc.CONSTRAINT_SCHEMA
            WHERE kcu.CONSTRAINT_SCHEMA = ?
              AND kcu.TABLE_NAME = ?
              AND kcu.COLUMN_NAME = ?
              AND kcu.REFERENCED_TABLE_NAME = ?
              AND kcu.REFERENCED_COLUMN_NAME = ?
        ', [$dbName, $table, $column, $refTable, $refColumn]);

        return $res[0] ?? null;
    }

    /**
     * Ensure a foreign key exists with the correct delete rule based on source and target.
     */
    private function ensureForeignKey(
        string $dbName,
        string $table,
        string $column,
        string $refTable,
        string $refColumn,
        string $rule,
        string $constraintName
    ): void {
        $fk = $this->getForeignKeyInfo($dbName, $table, $column, $refTable, $refColumn);
        $expectedRule = strtoupper($rule);

        if ($fk) {
            $currentRule = strtoupper($fk->DELETE_RULE);
            $matches = ($expectedRule === 'RESTRICT')
                ? in_array($currentRule, ['RESTRICT', 'NO ACTION'])
                : ($currentRule === $expectedRule);

            if ($matches) {
                return;
            }

            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }

        $deleteClause = ($expectedRule === 'CASCADE') ? 'ON DELETE CASCADE' : 'ON DELETE RESTRICT';
        DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$constraintName}` FOREIGN KEY (`{$column}`) REFERENCES `{$refTable}` (`{$refColumn}`) {$deleteClause}");
    }

    /**
     * Ensure a CHECK constraint exists, throwing exception if execution fails.
     */
    private function ensureCheckConstraint(string $dbName, string $table, string $constraintName, string $checkExpression): void
    {
        $exists = DB::select('
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = ? 
              AND TABLE_NAME = ? 
              AND CONSTRAINT_NAME = ? 
              AND CONSTRAINT_TYPE = "CHECK"
        ', [$dbName, $table, $constraintName]);

        if (empty($exists)) {
            DB::statement("ALTER TABLE `{$table}` ADD CONSTRAINT `{$constraintName}` CHECK ({$checkExpression})");
        }
    }

    /**
     * Drop a CHECK constraint if it exists.
     */
    private function dropCheckConstraintIfExists(string $dbName, string $table, string $constraintName): void
    {
        $exists = DB::select('
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = ? 
              AND TABLE_NAME = ? 
              AND CONSTRAINT_NAME = ? 
              AND CONSTRAINT_TYPE = "CHECK"
        ', [$dbName, $table, $constraintName]);

        if (!empty($exists)) {
            DB::statement("ALTER TABLE `{$table}` DROP CHECK `{$constraintName}`");
        }
    }

    /**
     * Ensure unique index exists on column.
     */
    private function ensureUniqueIndex(string $dbName, string $table, string $column, string $indexName): void
    {
        $exists = DB::select('
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = ? 
              AND TABLE_NAME = ? 
              AND COLUMN_NAME = ? 
              AND NON_UNIQUE = 0
        ', [$dbName, $table, $column]);

        if (empty($exists)) {
            DB::statement("ALTER TABLE `{$table}` ADD UNIQUE KEY `{$indexName}` (`{$column}`)");
        }
    }

    /**
     * Drop unique index if it exists.
     */
    private function dropUniqueIndexIfExists(string $dbName, string $table, string $indexName): void
    {
        $exists = DB::select('
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_SCHEMA = ? 
              AND TABLE_NAME = ? 
              AND INDEX_NAME = ?
        ', [$dbName, $table, $indexName]);

        if (!empty($exists)) {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$indexName}`");
        }
    }
};
