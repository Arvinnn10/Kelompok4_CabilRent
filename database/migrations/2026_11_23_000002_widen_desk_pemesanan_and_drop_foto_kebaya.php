<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 1. Memperbesar kapasitas kolom desk_pemesanan pada tabel orders menjadi VARCHAR(255).
     * 2. Menghapus kolom foto_kebaya yang tidak digunakan pada tabel products.
     */
    public function up(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'desk_pemesanan')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('desk_pemesanan', 255)->change();
            });
        }

        if (Schema::hasTable('products') && Schema::hasColumn('products', 'foto_kebaya')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('foto_kebaya');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'desk_pemesanan')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('desk_pemesanan', 50)->change();
            });
        }

        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'foto_kebaya')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('foto_kebaya', 255)->nullable()->after('status_produk');
            });
        }
    }
};
