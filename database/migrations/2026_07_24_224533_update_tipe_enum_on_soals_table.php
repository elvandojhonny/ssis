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
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE soals
            MODIFY tipe ENUM('pilihan_ganda')
            NOT NULL DEFAULT 'pilihan_ganda'
        ");
    }
};