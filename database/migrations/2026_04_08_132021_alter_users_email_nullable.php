<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus unique constraint lama (jika ada)
        $indexes = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_email_unique'");
        if (!empty($indexes)) {
            DB::statement("ALTER TABLE users DROP INDEX users_email_unique");
        }

        // Ubah kolom email menjadi nullable
        DB::statement("ALTER TABLE users MODIFY email VARCHAR(255) NULL");

        // (Opsional) Tambahkan unique constraint untuk email yang tidak NULL
        // Di MySQL, unique index mengizinkan banyak NULL, jadi ini aman
        DB::statement("ALTER TABLE users ADD UNIQUE INDEX users_email_unique (email)");
    }

    public function down(): void
    {
        // Hapus unique constraint
        DB::statement("ALTER TABLE users DROP INDEX users_email_unique");

        // Kembalikan ke NOT NULL (perlu mengisi NULL dengan nilai default terlebih dahulu)
        DB::statement("UPDATE users SET email = '' WHERE email IS NULL");
        DB::statement("ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL");

        // Tambahkan unique constraint kembali
        DB::statement("ALTER TABLE users ADD UNIQUE INDEX users_email_unique (email)");
    }
};
