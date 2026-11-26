<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'status_pesan')) {
                DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status_pesan` ENUM('pending', 'dikonfirmasi', 'sedang_dipinjam', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'pending'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'status_pesan')) {
                DB::statement("ALTER TABLE `orders` MODIFY COLUMN `status_pesan` ENUM('pending', 'dikonfirmasi', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'pending'");
            }
        }
    }
};
