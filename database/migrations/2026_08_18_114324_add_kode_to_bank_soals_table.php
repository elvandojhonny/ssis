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
                ->string('kode', 20)
                ->unique()
                ->nullable()
                ->after('tahun_ajaran_id');

        });
    }

    public function down(): void
    {
        Schema::table('bank_soals', function (Blueprint $table) {

            $table->dropUnique([
                'kode',
            ]);

            $table->dropColumn(
                'kode'
            );

        });
    }
};