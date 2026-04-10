<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'semester';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Relasi ke target_poin (satu semester memiliki banyak target poin).
     */
    public function targetPoin()
    {
        return $this->hasMany(TargetPoin::class);
    }

    /**
     * Relasi ke bukti (satu semester memiliki banyak bukti pengajuan).
     */
    public function bukti()
    {
        return $this->hasMany(Bukti::class);
    }
}
