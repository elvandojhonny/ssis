<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soals', function (Blueprint $table) {
    $table->id();

    $table->foreignId('bank_soal_id')
        ->constrained('bank_soals')
        ->cascadeOnDelete();

    $table->unsignedInteger('nomor');

    $table->text('pertanyaan');

    $table->text('pilihan_a');
    $table->text('pilihan_b');
    $table->text('pilihan_c');
    $table->text('pilihan_d');
    $table->text('pilihan_e')->nullable();

    $table->char('jawaban_benar', 1);

    $table->unsignedInteger('skor');

    $table->timestamps();

    $table->unique([
        'bank_soal_id',
        'nomor',
    ]);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};