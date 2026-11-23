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

        // 1. Sesuaikan kapasitas tb_bb menjadi VARCHAR(30) agar input deskriptif tidak terpotong
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'tb_bb')) {
            DB::statement("ALTER TABLE `orders` MODIFY COLUMN `tb_bb` VARCHAR(30) NOT NULL");
        }

        // 2. Sesuaikan kapasitas desk_pemesanan menjadi VARCHAR(150) agar nama produk panjang + tanggal acara tersimpan utuh
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'desk_pemesanan')) {
            DB::statement("ALTER TABLE `orders` MODIFY COLUMN `desk_pemesanan` VARCHAR(150) NOT NULL");
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

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'desk_pemesanan')) {
            DB::statement("ALTER TABLE `orders` MODIFY COLUMN `desk_pemesanan` VARCHAR(50) NOT NULL");
        }

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'tb_bb')) {
            DB::statement("ALTER TABLE `orders` MODIFY COLUMN `tb_bb` VARCHAR(20) NOT NULL");
        }
    }
};
