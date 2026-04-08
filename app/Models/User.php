<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'users';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Kolom yang harus disembunyikan untuk array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string',
    ];

    /**
     * Relasi ke Mahasiswa (hanya jika role mahasiswa).
     */
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    /**
     * Relasi ke DosenWali (hanya jika role dosen_wali).
     */
    public function dosenWali()
    {
        return $this->hasOne(DosenWali::class);
    }

    /**
     * Relasi ke Bukti (sebagai mahasiswa pengaju).
     */
    public function bukti()
    {
        return $this->hasMany(Bukti::class);
    }

    /**
     * Cek apakah user memiliki role tertentu.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function isDosenWali()
    {
        return $this->role === 'dosen_wali';
    }

    public function isKaprodi()
    {
        return $this->role === 'kaprodi';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
