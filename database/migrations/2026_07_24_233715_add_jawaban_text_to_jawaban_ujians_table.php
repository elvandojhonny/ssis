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
    Schema::table('jawaban_ujians', function (Blueprint $table) {

        $table->longText('jawaban_text')
              ->nullable()
              ->after('jawaban');

    });
}

public function down(): void
{
    Schema::table('jawaban_ujians', function (Blueprint $table) {

        $table->dropColumn('jawaban_text');

    });
}
};
