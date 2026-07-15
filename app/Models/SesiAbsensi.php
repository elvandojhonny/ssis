<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiAbsensi extends Model
{
    protected $fillable = [
        'kelas_id',
        'dibuka_oleh',
        'tanggal',
        'jenis',
        'waktu_mulai',
        'batas_terlambat',
        'waktu_selesai',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pembuka()
    {
        return $this->belongsTo(
            User::class,
            'dibuka_oleh'
        );
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function isPagi(): bool
    {
        return $this->jenis === 'pagi';
    }

    public function isSiang(): bool
    {
        return $this->jenis === 'siang';
    }
}