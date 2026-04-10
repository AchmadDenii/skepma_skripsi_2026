<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPoinSertifikat extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'master_poin_sertifikat';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'jenis_kegiatan_id',
        'peran',
        'tingkat',
        'kode',
        'poin',
        'bukti',
        'butuh_bukti',
        'aktif',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'poin' => 'integer',
        'butuh_bukti' => 'boolean',
        'aktif' => 'boolean',
        'tingkat' => 'string',
    ];

    /**
     * Relasi ke JenisKegiatan (inverse one-to-many).
     */
    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class);
    }

    /**
     * Relasi ke Bukti (satu master poin bisa dipakai banyak bukti).
     */
    public function bukti()
    {
        return $this->hasMany(Bukti::class, 'master_poin_id');
    }
}
