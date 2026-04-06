<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // CEK dulu sebelum tambah
        if (!Schema::hasColumn('jenis_kegiatan', 'deskripsi')) {
            Schema::table('jenis_kegiatan', function (Blueprint $table) {
                $table->string('deskripsi')->after('nama');
            });
        }

        // ENUM pakai raw SQL (tidak perlu cek)
        DB::statement("
            ALTER TABLE master_poin_sertifikat 
            MODIFY tingkat ENUM(
                'internasional',
                'nasional',
                'regional',
                'institut',
                'fakultas',
                'jurusan',
                'lokal'
            ) NULL
        ");
    }

    public function down(): void
    {
        if (Schema::hasColumn('jenis_kegiatan', 'deskripsi')) {
            Schema::table('jenis_kegiatan', function (Blueprint $table) {
                $table->dropColumn('deskripsi');
            });
        }
    }
};