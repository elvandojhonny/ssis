<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Soal extends Model
{
    protected $fillable = [
        'bank_soal_id',
        'tipe',
        'pertanyaan',
        'gambar_pertanyaan',

        'pilihan_a',
        'gambar_a',

        'pilihan_b',
        'gambar_b',

        'pilihan_c',
        'gambar_c',

        'pilihan_d',
        'gambar_d',

        'pilihan_e',
        'gambar_e',

        'jawaban_benar',
        'bobot',
        'nomor',
    ];

    protected function casts(): array
    {
        return [
            'bobot' => 'decimal:2',
        ];
    }

    public function bankSoal(): BelongsTo
    {
        return $this->belongsTo(BankSoal::class);
    }
}