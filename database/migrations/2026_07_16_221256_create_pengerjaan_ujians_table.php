<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengerjaan_ujians', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ujian_id')
                ->constrained('ujians')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->dateTime('waktu_mulai');

            $table->dateTime('waktu_selesai')
                ->nullable();

            /*
             * Batas akhir pengerjaan individual siswa.
             * Dihitung saat siswa benar-benar mulai ujian.
             */
            $table->dateTime('batas_waktu');

            $table->enum('status', [
                'mengerjakan',
                'selesai',
            ])->default('mengerjakan');

            $table->decimal(
                'nilai',
                8,
                2
            )->nullable();

            $table->timestamps();

            /*
             * Satu siswa hanya memiliki satu attempt
             * untuk satu ujian.
             */
            $table->unique([
                'ujian_id',
                'siswa_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengerjaan_ujians');
    }
};