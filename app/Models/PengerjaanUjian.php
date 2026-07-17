<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengerjaanUjian extends Model
{
    protected $fillable = [
        'ujian_id',
        'siswa_id',
        'waktu_mulai',
        'waktu_selesai',
        'batas_waktu',
        'status',
        'nilai',

        // Keamanan ujian
        'jumlah_pelanggaran',
        'diblokir_pada',
        'dibuka_blokir_oleh',
    ];

    protected function casts(): array
    {
        return [
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
            'batas_waktu' => 'datetime',
            'diblokir_pada' => 'datetime',

            'nilai' => 'decimal:2',
            'jumlah_pelanggaran' => 'integer',
            'dibuka_blokir_oleh' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Ujian
    |--------------------------------------------------------------------------
    */
    public function ujian(): BelongsTo
    {
        return $this->belongsTo(
            Ujian::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Siswa
    |--------------------------------------------------------------------------
    */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(
            Siswa::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Jawaban Ujian
    |--------------------------------------------------------------------------
    */
    public function jawabans(): HasMany
    {
        return $this->hasMany(
            JawabanUjian::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Operator yang Membuka Blokir
    |--------------------------------------------------------------------------
    */
    public function pembukaBlokir(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'dibuka_blokir_oleh'
        );
    }
}