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
            $table->enum('kategori', ['akademik','non-akademik']);
            $table->string('jenis_kegiatan', 100);
            $table->string('peran', 100);
            $table->string('tingkat', 100)->nullable();
            $table->string('kode', 50)->unique();
            $table->integer('poin');
            $table->timestamps();
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
