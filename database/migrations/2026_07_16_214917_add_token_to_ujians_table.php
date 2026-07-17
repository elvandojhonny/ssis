<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujians', function (Blueprint $table) {

            $table->string('token', 10)
                ->nullable()
                ->unique()
                ->after('status');

        });
    }

    public function down(): void
    {
        Schema::table('ujians', function (Blueprint $table) {

            $table->dropUnique([
                'token',
            ]);

            $table->dropColumn('token');

        });
    }
};