<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_pengantar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_warga_id')->constrained('master_warga')->onDelete('cascade');
            $table->string('nama');
            $table->string('nik', 20);
            $table->string('tempat');
            $table->date('tgl_lahir');
            $table->string('jenis_kelamin');
            $table->string('status_pernikahan');
            $table->string('kewarganegaraan');
            $table->string('agama');
            $table->string('pekerjaan');
            $table->text('alamat');
            $table->string('tujuan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pengantar');
    }
};
