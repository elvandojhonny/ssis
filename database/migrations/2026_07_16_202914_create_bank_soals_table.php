<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_soals', function (Blueprint $table) {
            $table->id();

            /*
             * Guru pemilik / pengupload bank soal.
             */
            $table->foreignId('guru_id')
                ->constrained('guru')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
             * Informasi bank soal.
             */
            $table->string('judul');

            $table->string('mata_pelajaran');

            $table->unsignedTinyInteger('tingkat');

            $table->text('deskripsi')
                ->nullable();

            /*
             * Status proses upload/import.
             */
            $table->enum('status', [
                'diproses',
                'siap',
                'gagal',
            ])->default('diproses');

            /*
             * Informasi file sumber.
             */
            $table->string('nama_file')
                ->nullable();

            $table->timestamps();

            $table->index([
                'guru_id',
                'tingkat',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_soals');
    }
};