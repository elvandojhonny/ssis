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

                $table->unique(
                    [
                        'ujian_id',
                        'siswa_id',
                    ],
                    'pengerjaan_ujian_siswa_unique'
                );

            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'pengerjaan_ujians',
            function (Blueprint $table) {

                $table->dropUnique(
                    'pengerjaan_ujian_siswa_unique'
                );

            }
        );
    }
};