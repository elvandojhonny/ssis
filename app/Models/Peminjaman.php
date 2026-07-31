<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'petugas_id',
        'siswa_id',
        'guru_id',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(
            DetailPeminjaman::class,
            'peminjaman_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getNamaPeminjamAttribute(): string
    {
        if ($this->siswa) {
            return $this->siswa->nama;
        }

        if ($this->guru) {
            return $this->guru->nama;
        }

        return '-';
    }

    public function getJenisPeminjamAttribute(): string
    {
        if ($this->siswa) {
            return 'Siswa';
        }

        if ($this->guru) {
            return 'Guru';
        }

        return '-';
    }

    public function getJumlahBukuAttribute(): int
    {
        return $this->detailPeminjaman->sum('jumlah');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {

            'dipinjam' => 'warning',

            'dikembalikan' => 'success',

            'terlambat' => 'danger',

            default => 'secondary',

        };
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function isDipinjam(): bool
    {
        return $this->status === 'dipinjam';
    }

    public function isDikembalikan(): bool
    {
        return $this->status === 'dikembalikan';
    }

    public function isTerlambat(): bool
    {
        return $this->status === 'terlambat';
    }

    public function updateStatusTerlambat(): void
    {
        if (
            $this->status === 'dipinjam'
            && now()->greaterThan($this->tanggal_jatuh_tempo)
        ) {

            $this->update([
                'status' => 'terlambat',
            ]);
        }
    }
}