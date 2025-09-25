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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();

            // Relasi ke master_warga
            $table->unsignedBigInteger('warga_id')->nullable();
            $table->foreign('warga_id')
                  ->references('id')
                  ->on('master_warga')
                  ->onDelete('cascade');

            $table->date('tanggal');
            $table->string('jenis');
            $table->decimal('jumlah', 12, 2);
            $table->text('keterangan')->nullable();

            // Tambahan bukti bayar (gambar)
            $table->string('bukti_bayar')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            // hapus foreign key
            $table->dropForeign(['warga_id']);
            $table->dropColumn('warga_id');

            // hapus kolom bukti_bayar
            $table->dropColumn('bukti_bayar');
        });

        Schema::dropIfExists('pembayarans');
    }
};
