<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menambahkan nilai 'provinsi' ke dalam enum tingkat
        DB::statement("ALTER TABLE master_poin_sertifikat MODIFY tingkat ENUM('internasional','nasional','regional','institut','fakultas','jurusan','lokal') NULL");
    }

    public function down(): void
    {
        // Kembalikan ke enum semula (tanpa 'provinsi')
        DB::statement("ALTER TABLE master_poin_sertifikat MODIFY tingkat ENUM('internasional','nasional','wilayah','lokal','jurusan') NULL");
    }
};
