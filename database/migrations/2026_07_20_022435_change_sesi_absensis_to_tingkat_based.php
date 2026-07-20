<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Buat index khusus untuk foreign key kelas_id.
         * Dengan begitu MySQL tidak lagi bergantung
         * pada unique index lama.
         */
        Schema::table('sesi_absensis', function (Blueprint $table) {
            $table->index(
                'kelas_id',
                'sesi_absensis_kelas_id_index'
            );
        });

        /*
         * Sekarang unique constraint lama aman dihapus.
         */
        Schema::table('sesi_absensis', function (Blueprint $table) {
            $table->dropUnique(
                'sesi_kelas_tanggal_jenis_unique'
            );
        });

        /*
         * kelas_id dibuat nullable karena sesi baru
         * akan menggunakan tingkat.
         */
        Schema::table('sesi_absensis', function (Blueprint $table) {
            $table->foreignId('kelas_id')
                ->nullable()
                ->change();
        });

        /*
         * Satu tingkat hanya boleh memiliki
         * satu sesi pagi dan satu sesi siang per hari.
         */
        Schema::table('sesi_absensis', function (Blueprint $table) {
            $table->unique(
                ['tingkat', 'tanggal', 'jenis'],
                'sesi_tingkat_tanggal_jenis_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('sesi_absensis', function (Blueprint $table) {
            $table->dropUnique(
                'sesi_tingkat_tanggal_jenis_unique'
            );
        });

        /*
         * Perhatian: rollback hanya aman jika tidak ada
         * sesi dengan kelas_id NULL.
         */
        Schema::table('sesi_absensis', function (Blueprint $table) {
            $table->foreignId('kelas_id')
                ->nullable(false)
                ->change();
        });

        Schema::table('sesi_absensis', function (Blueprint $table) {
            $table->unique(
                ['kelas_id', 'tanggal', 'jenis'],
                'sesi_kelas_tanggal_jenis_unique'
            );
        });

        Schema::table('sesi_absensis', function (Blueprint $table) {
            $table->dropIndex(
                'sesi_absensis_kelas_id_index'
            );
        });
    }
};