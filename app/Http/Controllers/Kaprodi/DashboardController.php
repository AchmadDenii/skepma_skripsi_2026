<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Bukti;
use App\Models\Semester;

class DashboardController extends Controller
{
    public function index()
    {
        // Total mahasiswa
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();

        // Total bukti (semua status)
        $totalBukti = Bukti::count();

        // Status bukti
        $approved = Bukti::where('status', 'approved')->count();
        $pending  = Bukti::where('status', 'pending')->count();
        $rejected = Bukti::where('status', 'rejected')->count();

        // Jumlah mahasiswa yang belum mencapai target poin (distinct)
        $kurangPoin = $this->getMahasiswaKurangPoinCount();

        // Statistik per semester (approved bukti)
        $statistikSemester = Bukti::where('status', 'approved')
            ->join('semester', 'bukti.semester_id', '=', 'semester.id')
            ->select('semester.id', 'semester.nama', DB::raw('COUNT(*) as total'))
            ->groupBy('semester.id', 'semester.nama')
            ->orderBy('semester.id')
            ->get();

        // Statistik per jenis kegiatan (approved bukti)
        $statistikJenis = Bukti::where('bukti.status', 'approved')
            ->join('master_poin_sertifikat', 'bukti.master_poin_id', '=', 'master_poin_sertifikat.id')
            ->join('jenis_kegiatan', 'master_poin_sertifikat.jenis_kegiatan_id', '=', 'jenis_kegiatan.id')
            ->select('jenis_kegiatan.nama as jenis_kegiatan', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_kegiatan.nama')
            ->get();

        // List bukti approved terbaru (5)
        $listBukti = Bukti::where('status', 'approved')
            ->join('users', 'bukti.user_id', '=', 'users.id')
            ->join('master_poin_sertifikat', 'bukti.master_poin_id', '=', 'master_poin_sertifikat.id')
            ->join('jenis_kegiatan', 'master_poin_sertifikat.jenis_kegiatan_id', '=', 'jenis_kegiatan.id')
            ->select(
                'bukti.id',
                'users.name as nama_mahasiswa',
                'jenis_kegiatan.nama as jenis_kegiatan',
                'bukti.created_at'
            )
            ->orderBy('bukti.created_at', 'desc')
            ->limit(5)
            ->get();

        // Daftar tahun (dari created_at bukti)
        $tahunList = Bukti::selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Daftar semester untuk filter
        $semesterList = Semester::orderBy('tanggal_mulai', 'asc')->get();

        return view('kaprodi.dashboard', compact(
            'totalMahasiswa',
            'totalBukti',
            'approved',
            'pending',
            'rejected',
            'statistikSemester',
            'statistikJenis',
            'listBukti',
            'tahunList',
            'semesterList',
            'kurangPoin'
        ));
    }

    /**
     * Menghitung jumlah mahasiswa yang memiliki kekurangan poin di minimal satu semester.
     */
    private function getMahasiswaKurangPoinCount()
    {
        // Subquery poin dicapai per mahasiswa per semester
        $poinDicapai = DB::table('bukti')
            ->join('master_poin_sertifikat', 'bukti.master_poin_id', '=', 'master_poin_sertifikat.id')
            ->select(
                'bukti.user_id',
                'bukti.semester_id',
                DB::raw('COALESCE(SUM(master_poin_sertifikat.poin), 0) as poin_dicapai')
            )
            ->groupBy('bukti.user_id', 'bukti.semester_id');

        // Mahasiswa yang poin dicapainya < minimal poin di semester manapun
        $mahasiswaIds = DB::table('target_poin')
            ->join('semester', 'target_poin.semester_id', '=', 'semester.id')
            ->leftJoinSub($poinDicapai, 'dicapai', function ($join) {
                $join->on('dicapai.semester_id', '=', 'target_poin.semester_id');
            })
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'dicapai.user_id')
                    ->orWhereNull('dicapai.user_id');
            })
            ->where('users.role', 'mahasiswa')
            ->select('users.id')
            ->groupBy('users.id', 'target_poin.semester_id', 'target_poin.minimal_poin')
            ->havingRaw('COALESCE(MAX(dicapai.poin_dicapai), 0) < target_poin.minimal_poin')
            ->distinct('users.id')
            ->pluck('users.id');

        return $mahasiswaIds->count();
    }

    /**
     * Endpoint AJAX untuk grafik semester.
     */
    public function grafikSemester(Request $request)
    {
        $tahun = $request->tahun;
        $semesterId = $request->semester_id;

        $query = Bukti::where('status', 'approved')
            ->join('semester', 'bukti.semester_id', '=', 'semester.id');

        if ($tahun) {
            $query->whereYear('bukti.created_at', $tahun);
        }
        if ($semesterId) {
            $query->where('bukti.semester_id', $semesterId);
        }

        $data = $query->select(
            'semester.id',
            'semester.nama as semester',
            DB::raw('COUNT(bukti.id) as total')
        )
            ->groupBy('semester.id', 'semester.nama')
            ->orderBy('semester.id')
            ->get();

        return response()->json($data);
    }

    /**
     * Endpoint AJAX untuk filter dashboard (semester, jenis, list).
     */
    public function filter(Request $request)
    {
        $tahun = $request->tahun;
        $semesterId = $request->semester_id;

        $statistikSemester = $this->getFilteredStatistikSemester($tahun, $semesterId);
        $statistikJenis    = $this->getFilteredStatistikJenis($tahun, $semesterId);
        $listBukti         = $this->getFilteredListBukti($tahun, $semesterId);

        return response()->json([
            'semester' => $statistikSemester,
            'jenis'    => $statistikJenis,
            'list'     => $listBukti,
        ]);
    }

    private function getFilteredStatistikSemester($tahun, $semesterId)
    {
        $query = Bukti::where('status', 'approved')
            ->join('semester', 'bukti.semester_id', '=', 'semester.id');

        if ($tahun) {
            $query->whereYear('bukti.created_at', $tahun);
        }
        if ($semesterId) {
            $query->where('bukti.semester_id', $semesterId);
        }

        return $query->select(
            'semester.id',
            'semester.nama as label',
            DB::raw('COUNT(bukti.id) as total')
        )
            ->groupBy('semester.id', 'semester.nama')
            ->orderBy('semester.id')
            ->get();
    }

    private function getFilteredStatistikJenis($tahun, $semesterId)
    {
        $query = Bukti::where('bukti.status', 'approved')
            ->join('master_poin_sertifikat', 'bukti.master_poin_id', '=', 'master_poin_sertifikat.id')
            ->join('jenis_kegiatan', 'master_poin_sertifikat.jenis_kegiatan_id', '=', 'jenis_kegiatan.id');

        if ($tahun) {
            $query->whereYear('bukti.created_at', $tahun);
        }
        if ($semesterId) {
            $query->where('bukti.semester_id', $semesterId);
        }

        return $query->select(
            'jenis_kegiatan.nama as jenis_kegiatan',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('jenis_kegiatan.nama')
            ->get();
    }

    private function getFilteredListBukti($tahun, $semesterId)
    {
        $query = Bukti::where('bukti.status', 'approved')
            ->join('users', 'bukti.user_id', '=', 'users.id')
            ->join('master_poin_sertifikat', 'bukti.master_poin_id', '=', 'master_poin_sertifikat.id')
            ->join('jenis_kegiatan', 'master_poin_sertifikat.jenis_kegiatan_id', '=', 'jenis_kegiatan.id');

        if ($tahun) {
            $query->whereYear('bukti.created_at', $tahun);
        }
        if ($semesterId) {
            $query->where('bukti.semester_id', $semesterId);
        }

        return $query->select(
            'bukti.id',
            'users.name as nama_mahasiswa',
            'jenis_kegiatan.nama as jenis_kegiatan',
            'bukti.created_at'
        )
            ->orderBy('bukti.created_at', 'desc')
            ->limit(5)
            ->get();
    }
}
