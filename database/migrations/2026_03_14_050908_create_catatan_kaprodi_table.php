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
        Schema::create('catatan_kaprodi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kaprodi_id');
            $table->unsignedBigInteger('dosen_id');
            $table->text('catatan');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_kaprodi');
    }
};
