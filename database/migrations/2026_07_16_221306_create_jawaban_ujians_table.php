<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_ujians', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pengerjaan_ujian_id')
                ->constrained('pengerjaan_ujians')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('soal_id')
                ->constrained('soals')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
             * Jawaban siswa: A, B, C, D, atau E.
             */
            $table->char(
                'jawaban',
                1
            )->nullable();

            $table->boolean('is_benar')
                ->nullable();

            $table->decimal(
                'skor',
                8,
                2
            )->default(0);

            $table->timestamps();

            /*
             * Satu soal hanya memiliki satu jawaban
             * dalam satu pengerjaan.
             */
            $table->unique([
                'pengerjaan_ujian_id',
                'soal_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_ujians');
    }
};