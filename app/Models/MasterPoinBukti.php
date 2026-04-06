<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPoinbukti extends Model
{
    use HasFactory;

    protected $table = 'master_poin_sertifikat';

    protected $fillable = [
        'kelompok_kegiatan',
        'jenis_kegiatan_id', 
        'tingkat',
        'peran',
        'kode',
        'poin',
    ];
}