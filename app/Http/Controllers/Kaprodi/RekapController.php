<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        // ====== DATA DROPDOWN ======
        $tahunList = DB::table('bukti')
            ->selectRaw('YEAR(tanggal_kegiatan) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $jenisList = DB::table('master_poin_sertifikat')
            ->orderBy('jenis_kegiatan')
            ->pluck('jenis_kegiatan');

        // ====== QUERY UTAMA ======
        $query = DB::table('bukti')
            ->join('users as mhs', 'mhs.id', '=', 'bukti.user_id')
            ->leftJoin('users as dosen', 'dosen.id', '=', 'bukti.dosen_id')
            ->join(
                'master_poin_sertifikat',
                'master_poin_sertifikat.id',
                '=',
                'bukti.master_poin_id'
            )
            ->select(
                'bukti.id',
                'mhs.name as nama_mahasiswa',
                'mhs.username',
                'dosen.name as nama_dosen',
                'master_poin_sertifikat.jenis_kegiatan',
                'master_poin_sertifikat.tingkat as tingkatan',
                'bukti.tanggal_kegiatan',
                'bukti.file',
                'bukti.status'
            );

        // ====== FILTER ======
        if ($request->tahun) {
            $query->whereYear('bukti.tanggal_kegiatan', $request->tahun);
        }

        if ($request->semester) {
            if ($request->semester === 'ganjil') {
                $query->whereMonth('bukti.tanggal_kegiatan', '>=', 8)
                      ->orWhereMonth('bukti.tanggal_kegiatan', '<=', 1);
            } elseif ($request->semester === 'genap') {
                $query->whereBetween(
                    DB::raw('MONTH(bukti.tanggal_kegiatan)'), [2, 7]
                );
            }
        }

        if ($request->jenis) {
            $query->where(
                'master_poin_sertifikat.jenis_kegiatan',
                $request->jenis
            );
        }

        $data = $query
            ->orderByDesc('bukti.tanggal_kegiatan')
            ->get();

        return view('kaprodi.rekap', compact(
            'data',
            'tahunList',
            'jenisList'
        ));
    }
    
    public function daftarMahasiswa()
    {
        $targetPoin = 1500;

        $data = DB::table('users as mhs')
            ->where('mhs.role', 'mahasiswa')

            ->leftJoin('dosen_mahasiswa', 'dosen_mahasiswa.mahasiswa_id', '=', 'mhs.id')
            ->leftJoin('users as dosen', 'dosen.id', '=', 'dosen_mahasiswa.dosen_id')

            ->leftJoin('bukti', function ($join) {
                $join->on('bukti.user_id', '=', 'mhs.id')
                    ->where('bukti.status', 'approved');
            })

            ->leftJoin(
                'master_poin_sertifikat',
                'master_poin_sertifikat.id',
                '=',
                'bukti.master_poin_id'
            )

            ->select(
                'mhs.id',
                'mhs.name',
                'mhs.username',
                'dosen.name as nama_dosen',

                DB::raw('COALESCE(SUM(master_poin_sertifikat.poin),0) as total_poin')
            )

            ->groupBy(
                'mhs.id',
                'mhs.name',
                'mhs.username',
                'dosen.name'
            )

            ->orderBy('mhs.name')
            ->get()
            ->map(function ($mhs) use ($targetPoin) {

                $mhs->progress = $targetPoin > 0
                    ? round(($mhs->total_poin / $targetPoin) * 100)
                    : 0;

                $mhs->poin_kurang = max(0, $targetPoin - $mhs->total_poin);

                return $mhs;
            });

        return view('kaprodi.mahasiswa.index', compact(
            'data',
            'targetPoin'
        ));
    }
}