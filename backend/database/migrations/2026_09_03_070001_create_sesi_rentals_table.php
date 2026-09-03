<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('perangkat_id')->constrained('perangkats')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('kode_sesi')->unique();
            $table->unsignedInteger('durasi');
            $table->decimal('harga', 10, 2);
            $table->string('status');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_rentals');
    }
};
