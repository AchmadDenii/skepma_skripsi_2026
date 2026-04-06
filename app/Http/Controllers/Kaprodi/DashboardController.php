<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // total mahasiswa
        $totalMahasiswa = DB::table('users')
            ->where('role', 'mahasiswa')
            ->count();

        // perhitungan poin
        $rekap = DB::table('target_poin as tp')
            ->join('semester as s', 's.id', '=', 'tp.semester_id')
            ->leftJoin('bukti as sf', 'sf.semester_id', '=', 'tp.semester_id')
            ->leftJoin('master_poin_sertifikat as mps', 'mps.id', '=', 'sf.master_poin_id')
            ->select(
                'tp.semester_id',
                'tp.minimal_poin',
                DB::raw('COALESCE(SUM(mps.poin),0) as total_poin')
            )
            ->groupBy('tp.semester_id', 'tp.minimal_poin')
            ->get();

        $lulus = 0;
        $belum = 0;

        foreach ($rekap as $r) {
            if ($r->total_poin >= $r->minimal_poin) {
                $lulus++;
            } else {
                $belum++;
            }
        }

        // total poin kurang
        $peringatan = DB::select("
            SELECT 
                u.name,
                tp.semester_id,
                tp.minimal_poin,
                COALESCE(SUM(mps.poin),0) AS poin_dicapai,
                (tp.minimal_poin - COALESCE(SUM(mps.poin),0)) AS kekurangan
            FROM users u
            JOIN target_poin tp
            LEFT JOIN bukti s ON s.user_id = u.id AND s.semester_id = tp.semester_id
            LEFT JOIN master_poin_sertifikat mps ON mps.id = s.master_poin_id
            WHERE u.role = 'mahasiswa'
            GROUP BY u.id, tp.semester_id, tp.minimal_poin
            HAVING kekurangan > 0
            ORDER BY kekurangan DESC
            LIMIT 5
        ");

        // total status
        $approved = DB::table('bukti')->where('status','approved')->count();
        $pending  = DB::table('bukti')->where('status','pending')->count();
        $rejected = DB::table('bukti')->where('status','rejected')->count();

        // mahasiswa kurang target
        $kurangPoin = DB::table('target_poin as tp')
        ->join(DB::raw("(
            SELECT
                u.id,
                s.semester_id,
                COALESCE(SUM(mps.poin),0) AS poin_dicapai
            FROM users u
            LEFT JOIN bukti s ON s.user_id = u.id
            LEFT JOIN master_poin_sertifikat mps ON mps.id = s.master_poin_id
            WHERE u.role = 'mahasiswa'
            GROUP BY u.id, s.semester_id
        ) as hasil"), function ($join) {
            $join->on('hasil.semester_id', '=', 'tp.semester_id');
        })
        ->whereRaw('hasil.poin_dicapai < tp.minimal_poin')
        ->distinct()
        ->count('hasil.id');

        // total bukti
        $totalbukti = DB::table('bukti')->count();

        // grafik semester
        $statistikSemester = DB::table('bukti as b')
            ->join('semester as s', 's.id', '=', 'b.semester_id')
            ->where('b.status', 'approved')
            ->select(
                's.id',
                's.nama',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('s.id', 's.nama')
            ->orderBy('s.id')
            ->get();

        // grafik jenis
        $statistikJenis = DB::table('bukti')
            ->join(
                'master_poin_sertifikat',
                'master_poin_sertifikat.id',
                '=',
                'bukti.master_poin_id'
            )
            ->where('bukti.status','approved')
            ->select(
                'master_poin_sertifikat.jenis_kegiatan',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('master_poin_sertifikat.jenis_kegiatan')
            ->get();

        // list terbaru
        $listbukti = DB::table('bukti')
            ->join('users', 'users.id', '=', 'bukti.user_id')
            ->join(
                'master_poin_sertifikat',
                'master_poin_sertifikat.id',
                '=',
                'bukti.master_poin_id'
            )
            ->where('bukti.status', 'approved')
            ->select(
                'bukti.id',
                'users.name as nama_mahasiswa',
                'master_poin_sertifikat.jenis_kegiatan',
                'bukti.created_at'
            )
            ->orderBy('bukti.created_at', 'desc')
            ->limit(5)
            ->get();

        // list tahun
        $tahunList = DB::table('bukti')
            ->selectRaw('YEAR(tanggal_kegiatan) as tahun')
            ->distinct()
            ->orderBy('tahun','desc')
            ->pluck('tahun');

        return view('kaprodi.dashboard', compact(
            'totalMahasiswa',
            'totalbukti',
            'approved',
            'pending',
            'rejected',
            'statistikSemester',
            'statistikJenis',
            'listbukti',
            'tahunList',
            'kurangPoin'
        ));

        $semesterList = DB::table('semester')
            ->orderBy('tanggal_mulai','asc')
            ->get();
        
        return view('kaprodi.dashboard', compact(
            'totalMahasiswa',
            'totalbukti',
            'approved',
            'pending',
            'rejected',
            'statistikSemester',
            'statistikJenis',
            'listbukti',
            'tahunList',
            'kurangPoin',
            'semesterList'
        ));
    }

    public function grafikSemester(Request $request)
    {
        $tahun = $request->tahun;
        $semesterId = $request->semester_id;

        $query = DB::table('bukti as b')
            ->join('semester as s', 's.id', '=', 'b.semester_id')
            ->where('b.status', 'approved');

        if ($tahun) {
            $query->whereYear('b.tanggal_kegiatan', $tahun);
        }

        if ($semesterId) {
            $query->where('b.semester_id', $semesterId);
        }

    $data = $query
        ->select(
            's.id',
            's.nama as semester',
            DB::raw('COUNT(b.id) as total')
        )
        ->groupBy('s.id','s.nama')
        ->orderBy('s.id')
        ->get();

        return response()->json($data);
    }

    public function filter(Request $request)
    {
        $query = DB::table('bukti as b')
            ->join('semester as s', 's.id', '=', 'b.semester_id')
            ->where('b.status', 'approved');

        if ($request->tahun) {
            $query->whereYear('b.tanggal_kegiatan', $request->tahun);
        }

        if ($request->semester_id) {
            $query->where('b.semester_id', $request->semester_id);
        }

        $statistikSemester = $query
            ->select(
                's.id',
                's.nama as label',
                DB::raw('COUNT(b.id) as total')
            )
            ->groupBy('s.id','s.nama')
            ->orderBy('s.id')
            ->get();

        $statistikJenis = DB::table('bukti as b')
            ->join('master_poin_sertifikat as mps','mps.id','=','b.master_poin_id')
            ->where('b.status','approved')
            ->when($request->tahun, fn($q)=>$q->whereYear('b.tanggal_kegiatan',$request->tahun))
            ->when($request->semester_id, fn($q)=>$q->where('b.semester_id',$request->semester_id))
            ->select(
                'mps.jenis_kegiatan',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('mps.jenis_kegiatan')
            ->get();

        $listbukti = DB::table('bukti as b')
            ->join('users','users.id','=','b.user_id')
            ->join('master_poin_sertifikat as mps','mps.id','=','b.master_poin_id')
            ->where('b.status','approved')
            ->when($request->tahun, fn($q)=>$q->whereYear('b.tanggal_kegiatan',$request->tahun))
            ->when($request->semester_id, fn($q)=>$q->where('b.semester_id',$request->semester_id))
            ->select(
                'b.id',
                'users.name as nama_mahasiswa',
                'mps.jenis_kegiatan',
                'b.created_at'
            )
            ->orderBy('b.created_at','desc')
            ->limit(5)
            ->get();

        return response()->json([
            'semester' => $statistikSemester,
            'jenis'    => $statistikJenis,
            'list'     => $listbukti
        ]);
    }
}