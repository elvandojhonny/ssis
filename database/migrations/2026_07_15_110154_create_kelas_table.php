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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajarans')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('nama', 50);

            $table->enum('tingkat', [
                'X',
                'XI',
                'XII'
            ]);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(
                ['tahun_ajaran_id', 'nama'],
                'kelas_tahun_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
