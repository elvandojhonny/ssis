<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'kelas_id',
        'nama_buku',
        'nama_penulis',
        'tahun_terbit',
        'jumlah',
        'jumlah_tersedia',
        'is_umum',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tahun_terbit' => 'integer',
            'is_umum' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
}