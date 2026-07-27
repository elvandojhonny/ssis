<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE soals
            MODIFY tipe ENUM('pilihan_ganda', 'essay')
            NOT NULL DEFAULT 'pilihan_ganda'
        ");

        DB::statement("ALTER TABLE soals MODIFY pilihan_a TEXT NULL");
        DB::statement("ALTER TABLE soals MODIFY pilihan_b TEXT NULL");
        DB::statement("ALTER TABLE soals MODIFY pilihan_c TEXT NULL");
        DB::statement("ALTER TABLE soals MODIFY pilihan_d TEXT NULL");
        DB::statement("ALTER TABLE soals MODIFY pilihan_e TEXT NULL");

        DB::statement("
            ALTER TABLE soals
            MODIFY jawaban_benar ENUM('A','B','C','D','E') NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE soals
            MODIFY tipe ENUM('pilihan_ganda')
            NOT NULL DEFAULT 'pilihan_ganda'
        ");

        DB::statement("ALTER TABLE soals MODIFY pilihan_a TEXT NOT NULL");
        DB::statement("ALTER TABLE soals MODIFY pilihan_b TEXT NOT NULL");
        DB::statement("ALTER TABLE soals MODIFY pilihan_c TEXT NOT NULL");
        DB::statement("ALTER TABLE soals MODIFY pilihan_d TEXT NOT NULL");
        DB::statement("ALTER TABLE soals MODIFY pilihan_e TEXT NULL");

        DB::statement("
            ALTER TABLE soals
            MODIFY jawaban_benar ENUM('A','B','C','D','E') NOT NULL
        ");
    }
};