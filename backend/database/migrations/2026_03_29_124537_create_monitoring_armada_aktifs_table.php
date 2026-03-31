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
        Schema::create('monitoring_armada_aktif', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('armada_id')->unique();
            $table->integer('last_recorded_km')->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->enum('spedo_status', ['HIDUP', 'MATI'])->default('HIDUP');
            $table->date('tanggal_mulai_monitoring');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('armada_id')
                ->references('id')->on('armada')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_armada_aktifs');
    }
};
