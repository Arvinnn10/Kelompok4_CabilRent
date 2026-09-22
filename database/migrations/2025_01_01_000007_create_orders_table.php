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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('products')->cascadeOnDelete();
            $table->string('no_whatsapp', 20);
            $table->string('instagram', 50)->nullable();
            $table->string('domisili', 100);
            $table->string('desk_pemesanan', 50);
            $table->integer('total_harga');
            $table->enum('status_pesan', ['pending', 'dikonfirmasi', 'selesai', 'dibatalkan'])->default('pending');
            $table->string('tb_bb', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
