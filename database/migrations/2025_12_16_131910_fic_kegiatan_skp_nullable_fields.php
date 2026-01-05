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
        Schema::table('kegiatan_skp', function (Blueprint $table) {
            $table->string('kategori')->nullable()->change();
            $table->integer('poin')->default(1)->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan_skp', function (Blueprint $table) {
            $table->string('kategori')->nullable(false)->change();
            $table->integer('poin')->nullable(false)->change();
        });
    }

};
