<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bukti extends Model
{
    use HasFactory;

    protected $table = 'bukti';

    protected $fillable = [
        'user_id',
        'dosen_id',
        'master_poin_id',
        'semester_id',
        'kategori',
        'nama_kegiatan',
        'tanggal_kegiatan',
        'file',
        'status',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function masterPoin()
    {
        return $this->belongsTo(MasterPoinbukti::class,'master_poin_id');
    }
}