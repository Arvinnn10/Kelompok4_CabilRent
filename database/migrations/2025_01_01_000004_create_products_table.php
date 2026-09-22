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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('categories')->cascadeOnDelete();
            $table->string('nama_produk', 100);
            $table->text('desk_produk')->nullable();
            $table->integer('harga_sewa');
            $table->string('foto_produk', 255);
            $table->enum('status_produk', ['tersedia', 'tidak_tersedia']);
            $table->string('foto_kebaya', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
