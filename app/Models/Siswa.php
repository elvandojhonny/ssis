<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Support\Str;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nis',
        'nisn',
        'nama',
        'jenis_kelamin',
        'alamat',
        'is_active',
        'qr_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected $hidden = [
        'qr_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (Siswa $siswa) {

            if (! $siswa->qr_token) {

                $siswa->qr_token =
                    self::generateUniqueQrToken();
            }
        });
    }

    public static function generateUniqueQrToken(): string
    {
        do {

            $token = Str::random(48);

        } while (
            self::where(
                'qr_token',
                $token
            )->exists()
        );

        return $token;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function sesiAbsensis()
    {
        return $this->hasMany(SesiAbsensi::class);
    }
}