<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKegiatan extends Model
{
    use HasFactory;

    protected $table = 'jenis_kegiatan';

    protected $fillable = [
        'nama',
        'kategori',
        'deskripsi',
    ];
    protected $casts = [
        'kategori' => 'string',
    ];
    public function masterPoinSertifikat()
    {
        return $this->hasMany(MasterPoinSertifikat::class);
    }
}
