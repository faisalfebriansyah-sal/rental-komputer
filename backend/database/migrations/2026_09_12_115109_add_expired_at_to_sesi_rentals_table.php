<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sesi_rentals', function (Blueprint $table) {
            $table->timestamp('expired_at')
                ->nullable()
                ->after('waktu_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('sesi_rentals', function (Blueprint $table) {
            $table->dropColumn('expired_at');
        });
    }
};