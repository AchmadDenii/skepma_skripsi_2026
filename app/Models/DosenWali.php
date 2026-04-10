<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenWali extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'dosen_wali';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'nip',
        'user_id',
    ];

    /**
     * Relasi ke User (dosen wali adalah pengguna dengan role 'dosen_wali').
     * Inverse one-to-one atau belongsTo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Mahasiswa (satu dosen wali membimbing banyak mahasiswa).
     * Asumsikan tabel mahasiswa memiliki foreign key 'dosen_wali_id'.
     */
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_wali_id', 'user_id');
    }
}
