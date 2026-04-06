<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_poin_sertifikat', function (Blueprint $table) {
            $table->unique(
                ['jenis_kegiatan_id', 'tingkat', 'peran'],
                'unique_master_poin_rule'
            );
        });
    }

    public function down(): void
    {
        Schema::table('master_poin_sertifikat', function (Blueprint $table) {
            $table->dropUnique('unique_master_poin_rule');
        });
    }
};