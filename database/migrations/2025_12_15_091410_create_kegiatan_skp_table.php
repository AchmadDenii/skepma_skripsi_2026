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
        Schema::create('kegiatan_skp', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kegiatan');
            $table->string('nama_kegiatan');
            $table->string('kategori');
            $table->integer('poin');
            $table->text('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_skp');
    }
};
