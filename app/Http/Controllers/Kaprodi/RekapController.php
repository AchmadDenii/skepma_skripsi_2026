<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // Data dropdown
        $tahunList = DB::table('bukti')
            ->selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $semesterList = DB::table('semester')->orderBy('id')->get();

        $jenisList = DB::table('jenis_kegiatan')
            ->orderBy('nama')
            ->pluck('nama');

        // Query utama
        $query = DB::table('bukti')
            ->join('users as mhs', 'mhs.id', '=', 'bukti.user_id')
            ->leftJoin('mahasiswa', 'mahasiswa.user_id', '=', 'mhs.id')
            ->leftJoin('users as dosen', 'dosen.id', '=', 'mahasiswa.dosen_wali_id')
            ->join('master_poin_sertifikat as mp', 'mp.id', '=', 'bukti.master_poin_id')
            ->join('jenis_kegiatan as jk', 'jk.id', '=', 'mp.jenis_kegiatan_id')
            ->select(
                'bukti.id',
                'mhs.name as nama_mahasiswa',
                'mhs.username',
                'dosen.name as nama_dosen',
                'jk.nama as jenis_kegiatan',
                'mp.tingkat as tingkatan',
                'bukti.created_at as tanggal_kegiatan',
                'bukti.file',
                'bukti.status'
            );

        // Filter tahun (berdasarkan created_at)
        if ($request->filled('tahun')) {
            $query->whereYear('bukti.created_at', $request->tahun);
        }

        // Filter semester (berdasarkan semester_id)
        if ($request->filled('semester_id')) {
            $query->where('bukti.semester_id', $request->semester_id);
        }

        // Filter jenis kegiatan
        if ($request->filled('jenis')) {
            $query->where('jk.nama', $request->jenis);
        }

        $data = $query->orderByDesc('bukti.created_at')->get();

        return view('kaprodi.rekap', compact('data', 'tahunList', 'semesterList', 'jenisList'));
    }

    public function daftarMahasiswa()
    {
        $targetPoin = 1500;

        $data = DB::table('users as mhs')
            ->where('mhs.role', 'mahasiswa')
            ->leftJoin('mahasiswa', 'mahasiswa.user_id', '=', 'mhs.id')
            ->leftJoin('users as dosen', 'dosen.id', '=', 'mahasiswa.dosen_wali_id')
            ->leftJoin('bukti', function ($join) {
                $join->on('bukti.user_id', '=', 'mhs.id')
                    ->where('bukti.status', 'approved');
            })
            ->leftJoin('master_poin_sertifikat as mp', 'mp.id', '=', 'bukti.master_poin_id')
            ->select(
                'mhs.id',
                'mhs.name',
                'mhs.username',
                'dosen.name as nama_dosen',
                DB::raw('COALESCE(SUM(mp.poin), 0) as total_poin')
            )
            ->groupBy('mhs.id', 'mhs.name', 'mhs.username', 'dosen.name')
            ->orderBy('mhs.name')
            ->get()
            ->map(function ($mhs) use ($targetPoin) {
                $mhs->progress = $targetPoin > 0 ? round(($mhs->total_poin / $targetPoin) * 100, 1) : 0;
                $mhs->poin_kurang = max(0, $targetPoin - $mhs->total_poin);
                return $mhs;
            });

        return view('kaprodi.mahasiswa.index', compact('data', 'targetPoin'));
    }
}
