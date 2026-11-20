<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Kolom status di tabel users untuk fitur blokir / aktif pengguna
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('status', ['aktif', 'diblokir'])->default('aktif')->after('role');
            });
        }

        // 2. Kolom deskripsi di tabel categories untuk deskripsi singkat kategori
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'deskripsi')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->text('deskripsi')->nullable()->after('slug');
            });
        }

        // 3. Kolom ukuran dan stok di tabel products
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'ukuran')) {
                    $table->string('ukuran', 30)->nullable()->after('harga_sewa');
                }
                if (!Schema::hasColumn('products', 'stok')) {
                    $table->integer('stok')->default(1)->after('ukuran');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'deskripsi')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('deskripsi');
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'ukuran')) {
                    $table->dropColumn('ukuran');
                }
                if (Schema::hasColumn('products', 'stok')) {
                    $table->dropColumn('stok');
                }
            });
        }
    }
};
