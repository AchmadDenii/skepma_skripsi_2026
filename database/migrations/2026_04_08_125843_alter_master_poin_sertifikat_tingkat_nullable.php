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
        DB::statement('ALTER TABLE master_poin_sertifikat MODIFY tingkat ENUM("internasional","nasional","wilayah","lokal","jurusan") NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE master_poin_sertifikat MODIFY tingkat ENUM("internasional","nasional","wilayah","lokal","jurusan") NOT NULL');
    }
};
