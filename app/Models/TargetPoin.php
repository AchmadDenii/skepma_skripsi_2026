<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetPoin extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'target_poin';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'semester_id',
        'minimal_poin',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'minimal_poin' => 'integer',
    ];

    /**
     * Relasi ke Semester (inverse one-to-many).
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
