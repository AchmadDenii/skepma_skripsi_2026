<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bukti extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     */
    protected $table = 'bukti';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'semester_id',
        'dosen_id',
        'master_poin_id',
        'file',
        'status',
        'catatan_dosen',
        'keterangan',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relasi ke User (mahasiswa pengaju).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Semester.
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Relasi ke DosenWali (dosen penilai).
     */
    public function dosen()
    {
        return $this->belongsTo(DosenWali::class, 'dosen_id');
    }

    /**
     * Relasi ke MasterPoinSertifikat (acuan poin).
     */
    public function masterPoin()
    {
        return $this->belongsTo(MasterPoinSertifikat::class, 'master_poin_id');
    }

    /**
     * Helper untuk mengecek status.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope untuk filter status.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
