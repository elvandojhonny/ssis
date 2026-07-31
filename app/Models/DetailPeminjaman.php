<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'peminjaman_id',
        'buku_id',
        'jumlah',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */

    public function peminjaman()
    {
        return $this->belongsTo(
            Peminjaman::class,
            'peminjaman_id'
        );
    }

    public function buku()
    {
        return $this->belongsTo(
            Buku::class,
            'buku_id'
        );
    }
    

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */

    public function getNamaBukuAttribute(): string
    {
        return $this->buku?->nama_buku ?? '-';
    }

    public function getNamaKelasAttribute(): string
    {
        return $this->buku?->kelas?->nama_kelas ?? '-';
    }

    public function getJumlahDipinjamAttribute(): int
    {
        return (int) $this->jumlah;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function tambahStok(): void
    {
        if ($this->buku) {

            $this->buku->increment(
                'jumlah_tersedia',
                $this->jumlah
            );

        }
    }

    public function kurangiStok(): void
    {
        if (!$this->buku) {
            return;
        }

        if ($this->buku->jumlah_tersedia < $this->jumlah) {

            throw new \Exception(
                "Stok buku {$this->buku->nama_buku} tidak mencukupi."
            );

        }

        $this->buku->decrement(
            'jumlah_tersedia',
            $this->jumlah
        );
    }
}