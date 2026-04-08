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
        Schema::create(
            'bukti',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
                $table->foreignId('dosen_id')->nullable()->constrained('dosen_wali')->onDelete('set null');
                $table->foreignId('master_poin_id')->constrained('master_poin_sertifikat')->cascadeOnDelete();
                $table->string('file', 255)->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('catatan_dosen')->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti');
    }
};
