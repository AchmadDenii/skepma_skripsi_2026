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
        Schema::create('master_poin_sertifikat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_kegiatan_id')
                ->constrained('jenis_kegiatan')
                ->cascadeOnDelete();
            $table->string('peran', 100);
            $table->enum('tingkat', [
                'internasional',
                'nasional',
                'wilayah',
                'lokal',
                'jurusan'
            ]);
            $table->string('kode', 50);
            $table->integer('poin')->unsigned();
            $table->string('bukti', 255)->nullable();
            $table->timestamps();
            $table->boolean('butuh_bukti')->default(1);
            $table->boolean('aktif')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_poin_sertifikat');
    }
};
