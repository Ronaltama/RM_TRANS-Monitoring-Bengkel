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
        Schema::create('riwayat_perawatan_komponen', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('komponen_armada_id');

            $table->integer('km_saat_selesai')->nullable();
            $table->date('tanggal_selesai');
            $table->text('catatan')->nullable();

            $table->foreign('komponen_armada_id')
                ->references('id')->on('komponen_armada')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_perawatan_komponens');
    }
};
