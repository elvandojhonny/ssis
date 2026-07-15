<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'sesi_absensi_id',
        'siswa_id',
        'waktu_absen',
        'status',
        'metode',
        'dicatat_oleh',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'waktu_absen' => 'datetime',
        ];
    }

    public function sesiAbsensi()
    {
        return $this->belongsTo(SesiAbsensi::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pencatat()
    {
        return $this->belongsTo(
            User::class,
            'dicatat_oleh'
        );
    }
}