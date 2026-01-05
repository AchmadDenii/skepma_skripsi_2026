<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPoinSertifikat extends Model
{
    use HasFactory;

    protected $table = 'master_poin_sertifikat';
    protected $fillable = [
        'kelompok_kegiatan',
        'tingkat',
        'peran',
        'kode',
        'poin',
    ];
}
