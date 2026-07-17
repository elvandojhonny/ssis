<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();

            /*
             * Bank soal yang digunakan.
             */
            $table->foreignId('bank_soal_id')
                ->constrained('bank_soals')
                ->restrictOnDelete();

            /*
             * Kelas peserta ujian.
             */
            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->restrictOnDelete();

            /*
             * Operator yang membuat ujian.
             * Mengacu langsung ke users.
             */
            $table->foreignId('dibuat_oleh')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('judul');

            $table->text('deskripsi')
                ->nullable();

            /*
             * Jadwal ujian.
             */
            $table->dateTime('waktu_mulai');

            $table->dateTime('waktu_selesai');

            /*
             * Durasi pengerjaan setiap siswa.
             */
            $table->unsignedInteger('durasi_menit');

            /*
             * Status publikasi.
             */
            $table->enum('status', [
                'draft',
                'dipublikasi',
                'selesai',
            ])->default('draft');

            $table->timestamps();

            $table->index([
                'kelas_id',
                'status',
            ]);

            $table->index([
                'waktu_mulai',
                'waktu_selesai',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujians');
    }
};