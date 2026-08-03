<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    public function petugas()
    {
        return $this->hasOne(Petugas::class, 'user_id', 'id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    public function sesiAbsensis()
    {
        return $this->hasMany(
            SesiAbsensi::class,
            'dibuka_oleh'
        );
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isPetugasAbsensi(): bool
    {
        return $this->role === 'petugas_absensi';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }
}