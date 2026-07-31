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
        Schema::create('peminjaman', function (Blueprint $table) {

            $table->id();

            $table->string('kode_peminjaman')->unique();

            $table->foreignId('petugas_id')
                ->constrained('petugas')
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->nullable()
                ->constrained('siswa')
                ->nullOnDelete();

            $table->foreignId('guru_id')
                ->nullable()
                ->constrained('guru')
                ->nullOnDelete();

            $table->date('tanggal_pinjam');

            $table->date('tanggal_jatuh_tempo');

            $table->date('tanggal_kembali')->nullable();

            $table->enum('status', [
                'dipinjam',
                'dikembalikan',
                'terlambat',
            ])->default('dipinjam');

            $table->text('catatan')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};