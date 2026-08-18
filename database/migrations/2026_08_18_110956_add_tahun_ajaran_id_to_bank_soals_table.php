<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_soals', function (Blueprint $table) {

            $table
                ->foreignId('tahun_ajaran_id')
                ->nullable()
                ->after('guru_id')
                ->constrained('tahun_ajarans')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('bank_soals', function (Blueprint $table) {

            $table->dropForeign([
                'tahun_ajaran_id'
            ]);

            $table->dropColumn(
                'tahun_ajaran_id'
            );

        });
    }
};