<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankSoal extends Model
{
    protected $fillable = [
        'guru_id',
        'tahun_ajaran_id',
        'kode',
        'judul',
        'mata_pelajaran',
        'tingkat',
        'deskripsi',
        'status',
        'is_archived',
        'nama_file',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(
            TahunAjaran::class
        );
    }

    public function soals()
    {
        return $this->hasMany(Soal::class)
            ->orderBy('nomor');
    }
}