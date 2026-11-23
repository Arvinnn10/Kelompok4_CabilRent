<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom kode_pesanan untuk pengelompokan transaksi checkout multi-item (BUG-007).
     */
    public function up(): void
    {
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'kode_pesanan')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('kode_pesanan', 50)->nullable()->index()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'kode_pesanan')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex(['kode_pesanan']);
                $table->dropColumn('kode_pesanan');
            });
        }
    }
};
