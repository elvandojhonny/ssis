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

                $table->unsignedTinyInteger(
                    'jumlah_pelanggaran'
                )->default(0);

                $table->timestamp(
                    'diblokir_pada'
                )->nullable();

                $table->foreignId(
                    'dibuka_blokir_oleh'
                )
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'pengerjaan_ujians',
            function (Blueprint $table) {

                $table->dropForeign([
                    'dibuka_blokir_oleh'
                ]);

                $table->dropColumn([
                    'jumlah_pelanggaran',
                    'diblokir_pada',
                    'dibuka_blokir_oleh',
                ]);

            }
        );
    }
};