<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuktiController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('bukti as b')
            ->join('users as u', 'b.user_id', '=', 'u.id')
            ->join('semester as s', 'b.semester_id', '=', 's.id')
            ->join('master_poin_sertifikat as mp', 'b.master_poin_id', '=', 'mp.id')
            ->select(
                'b.id',
                'u.name',
                'u.username',
                'b.keterangan as nama_kegiatan',   // kolom keterangan sebagai deskripsi kegiatan
                's.nama as semester',
                'mp.poin',
                'b.status',
                'b.created_at as tanggal_kegiatan', // gunakan created_at sebagai tanggal upload
                'b.file'
            );

        // Filter search mahasiswa (nama atau username)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('u.name', 'like', '%' . $request->search . '%')
                    ->orWhere('u.username', 'like', '%' . $request->search . '%');
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('b.status', $request->status);
        }

        // Filter semester
        if ($request->filled('semester')) {
            $query->where('b.semester_id', $request->semester);
        }

        $bukti = $query->orderBy('b.created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $semester = DB::table('semester')->orderBy('nama')->get();

        return view('admin.bukti.index', compact('bukti', 'semester'));
    }
}
