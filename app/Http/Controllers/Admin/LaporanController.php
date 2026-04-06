<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $tahunList = DB::table('bukti')
            ->selectRaw('YEAR(tanggal_kegiatan) as tahun')
            ->where('status', 'approved')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.laporan.index', compact('tahunList'));
    }

    public function cetak(Request $request)
    {
        $tahun = $request->tahun;
        $target = 1500;

        $query = DB::table('users')
            ->where('users.role', 'mahasiswa')
            ->leftJoin('bukti', function ($join) use ($tahun) {
                $join->on('users.id', '=', 'bukti.user_id')
                     ->where('bukti.status', 'approved');

                if ($tahun) {
                    $join->whereYear('bukti.tanggal_kegiatan', $tahun);
                }
            })
            ->leftJoin(
                'master_poin_sertifikat',
                'master_poin_sertifikat.id',
                '=',
                'bukti.master_poin_id'
            )
            ->select(
                'users.name',
                'users.username',
                DB::raw('COALESCE(SUM(master_poin_sertifikat.poin), 0) as total_poin')
            )
            ->groupBy('users.id', 'users.name', 'users.username')
            ->orderByDesc('total_poin')
            ->get();

        return view('admin.laporan.cetak', compact(
            'query',
            'tahun',
            'target'
        ));
    }
}
