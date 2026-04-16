<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan nilai '-' ke dalam enum tingkat
        DB::statement("ALTER TABLE master_poin_sertifikat MODIFY COLUMN tingkat ENUM('internasional','nasional','regional','institut','fakultas','jurusan','lokal','-') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum awal tanpa '-'
        DB::statement("ALTER TABLE master_poin_sertifikat MODIFY COLUMN tingkat ENUM('internasional','nasional','regional','institut','fakultas','jurusan','lokal') NULL");
    }
};
