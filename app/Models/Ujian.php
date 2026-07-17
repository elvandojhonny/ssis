<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ujian extends Model
{
    protected $fillable = [
        'bank_soal_id',
        'kelas_id',
        'dibuat_oleh',
        'judul',
        'deskripsi',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
        'status',
        'token',
    ];

    protected function casts(): array
    {
        return [
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
            'durasi_menit' => 'integer',
        ];
    }

    public function bankSoal(): BelongsTo
    {
        return $this->belongsTo(
            BankSoal::class
        );
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(
            Kelas::class
        );
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'dibuat_oleh'
        );
    }

    public static function generateUniqueToken(): string
    {
        do {

            $token = strtoupper(
                \Illuminate\Support\Str::random(6)
            );

        } while (
            self::where('token', $token)->exists()
        );

        return $token;
    }
}