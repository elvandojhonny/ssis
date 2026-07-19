<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'pengerjaan_ujians',
            function (Blueprint $table) {

                $table
                    ->json('urutan_soal')
                    ->nullable()
                    ->after('batas_waktu');

                $table
                    ->json('urutan_jawaban')
                    ->nullable()
                    ->after('urutan_soal');

            }
        );
    }


    public function down(): void
    {
        Schema::table(
            'pengerjaan_ujians',
            function (Blueprint $table) {

                $table->dropColumn([
                    'urutan_soal',
                    'urutan_jawaban',
                ]);

            }
        );
    }
};