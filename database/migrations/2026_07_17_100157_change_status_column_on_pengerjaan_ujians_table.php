<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'pengerjaan_ujians',
            function (Blueprint $table) {

                $table->string(
                    'status',
                    30
                )
                    ->default('mengerjakan')
                    ->change();

            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'pengerjaan_ujians',
            function (Blueprint $table) {

                $table->enum(
                    'status',
                    [
                        'mengerjakan',
                        'selesai',
                    ]
                )
                    ->default('mengerjakan')
                    ->change();

            }
        );
    }
};