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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('nis')->unique();

            $table->string('nisn')->nullable()->unique();

            $table->string('nama');

            $table->string('jenis_kelamin', 1)->nullable();

            $table->text('alamat')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
