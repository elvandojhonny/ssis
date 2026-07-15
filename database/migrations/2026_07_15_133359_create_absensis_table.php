<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();

        $table->foreignId('sesi_absensi_id')
            ->constrained('sesi_absensis')
            ->cascadeOnDelete();

        $table->foreignId('siswa_id')
            ->constrained('siswa')
            ->restrictOnDelete();

        $table->dateTime('waktu_absen')
            ->nullable();

        $table->enum('status', [
            'hadir',
            'terlambat',
            'izin',
            'sakit',
            'alpa',
        ]);

        $table->enum('metode', [
            'qr',
            'manual',
            'sistem',
        ])->default('qr');

        $table->foreignId('dicatat_oleh')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->text('keterangan')
            ->nullable();

        $table->timestamps();

        /*
         * Satu siswa hanya memiliki satu
         * catatan pada satu sesi absensi.
         */
        $table->unique([
            'sesi_absensi_id',
            'siswa_id',
        ]);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
