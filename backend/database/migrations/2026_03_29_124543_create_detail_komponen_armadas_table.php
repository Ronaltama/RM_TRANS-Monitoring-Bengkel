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
        Schema::create('detail_komponen_armada', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('komponen_armada_id');

            $table->string('nomor_seri', 50)->nullable();
            $table->string('nomor_stamp', 50)->nullable();

            $table->enum('posisi_ban', [
                'depan_kiri',
                'depan_kanan',
                'tengah_kiri',
                'tengah_kanan',
                'belakang_kiri',
                'belakang_kanan',
                'cadangan'
            ])->nullable();

            $table->enum('jenis_ban', ['ORI', 'VULK'])->nullable();

            $table->string('ukuran', 50)->nullable();
            $table->string('merk_tipe', 100)->nullable();
            $table->string('pemasok', 100)->nullable();

            $table->decimal('harga', 12, 2)->nullable();

            $table->date('tanggal_pemasangan')->nullable();
            $table->integer('km_pemasangan')->nullable();

            $table->decimal('jumlah_liter', 5, 1)->nullable();

            $table->date('tanggal_pelepasan')->nullable();
            $table->enum('status_ban_bekas', ['VULK', 'JUAL'])->nullable();

            $table->text('keterangan')->nullable();

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
        Schema::dropIfExists('detail_komponen_armadas');
    }
};
