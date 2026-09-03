<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_id')->constrained('sesi_rentals')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('jumlah', 10, 2);
            $table->string('status');
            $table->dateTime('waktu_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
