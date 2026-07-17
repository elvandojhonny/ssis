<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanUjian extends Model
{
    protected $fillable = [
        'pengerjaan_ujian_id',
        'soal_id',
        'jawaban',
        'is_benar',
        'skor',
    ];

    protected function casts(): array
    {
        return [
            'is_benar' => 'boolean',
            'skor' => 'decimal:2',
        ];
    }

    public function pengerjaanUjian(): BelongsTo
    {
        return $this->belongsTo(
            PengerjaanUjian::class
        );
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class);
    }
}