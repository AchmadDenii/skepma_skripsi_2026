<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {

            // FK ke kegiatan_skp
            if (!Schema::hasColumn('sertifikat', 'kegiatan_id')) {
                $table->foreignId('kegiatan_id')
                      ->after('user_id')
                      ->constrained('kegiatan_skp');
            }

            // jenis kegiatan manual
            if (!Schema::hasColumn('sertifikat', 'jenis_kegiatan')) {
                $table->string('jenis_kegiatan')->after('kegiatan_id');
            }

            // jenis dokumen enum
            if (!Schema::hasColumn('sertifikat', 'jenis_dokumen')) {
                $table->enum('jenis_dokumen', [
                    'sertifikat',
                    'surat_tugas',
                    'dokumen_digital_lain'
                ])->after('jenis_kegiatan');
            }

            // poin (diisi saat approved)
            if (!Schema::hasColumn('sertifikat', 'poin')) {
                $table->integer('poin')->nullable()->after('status');
            }

            // catatan dosen
            if (!Schema::hasColumn('sertifikat', 'catatan')) {
                $table->text('catatan')->nullable()->after('poin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sertifikat', function (Blueprint $table) {
            if (Schema::hasColumn('sertifikat', 'kegiatan_id')) {
                $table->dropForeign(['kegiatan_id']);
                $table->dropColumn('kegiatan_id');
            }

            $table->dropColumn([
                'jenis_kegiatan',
                'jenis_dokumen',
                'poin',
                'catatan'
            ]);
        });
    }
};