<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_warga', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik', 20)->unique();
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->text('alamat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_warga');
    }
};
