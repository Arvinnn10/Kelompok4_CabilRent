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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('email');
            }
            $table->string('password', 255)->nullable()->change();
            $table->string('no_telp', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     * Mengatasi BUG-016: Sanitasi record NULL sebelum revert kolom ke NOT NULL untuk mencegah SQL error 1138.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            \Illuminate\Support\Facades\DB::table('users')
                ->whereNull('password')
                ->update(['password' => '$2y$12$eInFwRqvzFz9N6E80yQ0heL1kG070Xk5r4v5S7F2c3k4l5m6n7o8p']);

            \Illuminate\Support\Facades\DB::table('users')
                ->whereNull('no_telp')
                ->update(['no_telp' => '-']);
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropColumn('google_id');
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
            $table->string('password', 255)->nullable(false)->change();
            $table->string('no_telp', 20)->nullable(false)->change();
        });
    }
};
