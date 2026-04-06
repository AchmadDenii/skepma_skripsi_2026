<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // total poin yang disetujui
        $totalPoin = DB::table('bukti')
            ->join('master_poin_sertifikat', 'bukti.master_poin_id', '=', 'master_poin_sertifikat.id')
            ->where('bukti.user_id', $user->id)
            ->where('bukti.status', 'approved')
            ->sum('master_poin_sertifikat.poin');

        // target sistem
        $targetKelulusan = 1500;

        // asumsi kuliah 8 semester
        $targetPerSemester = $targetKelulusan / 8;

        // progress mahasiswa
        $progress = ($totalPoin / $targetKelulusan) * 100;

        // poin yang masih kurang
        $poinKurang = max(0, $targetKelulusan - $totalPoin);

        // bukti terbaru
        $buktiTerbaru = DB::table('bukti')
            ->join('master_poin_sertifikat', 'bukti.master_poin_id', '=', 'master_poin_sertifikat.id')
            ->where('bukti.user_id', $user->id)
            ->select(
                'bukti.*',
                'master_poin_sertifikat.poin'
            )
            ->orderBy('bukti.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('mahasiswa.dashboard', [
            'mahasiswa'        => $user,
            'totalPoin'        => $totalPoin,
            'targetKelulusan'  => $targetKelulusan,
            'targetPerSemester'=> $targetPerSemester,
            'poinKurang'       => $poinKurang,
            'progress'         => $progress,
            'buktiTerbaru'     => $buktiTerbaru,
        ]);
    }
}