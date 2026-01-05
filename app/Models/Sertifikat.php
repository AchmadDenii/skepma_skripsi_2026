<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikat';

    protected $fillable = [
        'user_id',
        'master_poin_id',
        'kategori',
        'nama_kegiatan',
        'tanggal_kegiatan',
        'file',
        'status',
        'keterangan',
    ];

    // relasi ke user (mahasiswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relasi ke master poin (ACUAN, bukan hitungan live)
    public function masterPoin()
    {
        return $this->belongsTo(
            MasterPoinSertifikat::class,
            'master_poin_id'
        );
    }
}