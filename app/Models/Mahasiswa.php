<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'mahasiswa';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'npm',
        'prodi',
        'angkatan',
        'semester',
        'ipk',
        'dosen_wali_id',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'angkatan' => 'integer',
        'semester' => 'integer',
        'ipk' => 'decimal:2',
    ];

    /**
     * Relasi ke User (akun mahasiswa).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Dosen Wali (user dengan role dosen_wali).
     * Karena dosen_wali_id mengacu ke users.id.
     */
    public function dosenWali()
    {
        return $this->belongsTo(DosenWali::class, 'dosen_wali_id', 'user_id');
    }

    /**
     * Relasi ke Bukti (satu mahasiswa punya banyak pengajuan bukti).
     */
    public function bukti()
    {
        return $this->hasMany(Bukti::class, 'user_id', 'user_id');
    }
}
