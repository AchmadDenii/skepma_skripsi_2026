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
        Schema::create('bukti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('semester_id')
                ->constrained('semester')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('dosen_id');
            $table->foreignId('master_poin_id')
                ->constrained('master_poin_sertifikat')
                ->cascadeOnDelete();
            $table->string('kategori',50);
            $table->string('nama_kegiatan',255);
            $table->date('tanggal_kegiatan');
            $table->string('file',255);
            $table->enum('status',[
                'pending',
                'approved',
                'rejected'
            ])->default('pending');
            $table->text('catatan_dosen')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti');
    }
};
