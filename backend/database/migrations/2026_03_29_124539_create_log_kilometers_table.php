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
        Schema::create('log_kilometer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('armada_id');
            $table->integer('odometer_km');
            $table->integer('delta_km');
            $table->date('tgl_input');

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
        Schema::dropIfExists('log_kilometers');
    }
};
