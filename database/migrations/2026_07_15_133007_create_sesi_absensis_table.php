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
    Schema::create('sesi_absensis', function (Blueprint $table) {
        $table->id();

        $table->foreignId('kelas_id')
            ->constrained('kelas')
            ->restrictOnDelete();

        $table->foreignId('dibuka_oleh')
            ->constrained('users')
            ->restrictOnDelete();

        $table->date('tanggal');

        $table->enum('jenis', [
            'pagi',
            'siang',
        ]);

        $table->time('waktu_mulai');

        $table->time('batas_terlambat')
            ->nullable();

        $table->time('waktu_selesai');

        $table->enum('status', [
            'aktif',
            'selesai',
        ])->default('aktif');

        $table->timestamps();

        /*
         * Satu kelas hanya boleh memiliki
         * satu sesi pagi dan satu sesi siang
         * dalam satu hari.
         */
        $table->unique(
            ['kelas_id', 'tanggal', 'jenis'],
            'sesi_kelas_tanggal_jenis_unique'
        );
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_absensis');
    }
};
