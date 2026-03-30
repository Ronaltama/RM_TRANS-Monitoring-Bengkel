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
        Schema::create('komponen_armada', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('monitoring_armada_id');
            $table->uuid('kategori_komponen_id');

            $table->string('nama_komponen', 100);
            $table->enum('tipe_pelacakan', ['km', 'date', 'days']);

            $table->integer('target_km')->nullable();
            $table->date('target_tanggal')->nullable();
            $table->integer('target_hari')->nullable();

            $table->integer('progres_saat_ini')->default(0);

            $table->integer('km_terakhir_perawatan')->nullable();
            $table->date('tanggal_terakhir_perawatan')->nullable();

            $table->enum('status', ['active', 'completed', 'overdue', 'removed']);

            $table->timestamps();

            $table->foreign('monitoring_armada_id')
                ->references('id')->on('monitoring_armada_aktif')
                ->cascadeOnDelete();

            $table->foreign('kategori_komponen_id')
                ->references('id')->on('category_componen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_armadas');
    }
};
