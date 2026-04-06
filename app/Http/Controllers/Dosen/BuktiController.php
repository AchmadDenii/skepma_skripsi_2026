<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class buktiController extends Controller
{
    // DASHBOARD
    public function dashboard()
    {
        $dosenId = auth()->id();

        $mahasiswaIds = DB::table('dosen_mahasiswa')
            ->where('dosen_id', $dosenId)
            ->pluck('mahasiswa_id');

        $totalMahasiswa = $mahasiswaIds->count();

        $totalPending = DB::table('bukti')
            ->whereIn('user_id', $mahasiswaIds)
            ->where('status', 'pending')
            ->count();

        $totalApproved = DB::table('bukti')
            ->whereIn('user_id', $mahasiswaIds)
            ->where('status', 'approved')
            ->count();

        $totalRejected = DB::table('bukti')
            ->whereIn('user_id', $mahasiswaIds)
            ->where('status', 'rejected')
            ->count();

        $buktiPending = DB::table('bukti')
            ->join('users', 'users.id', '=', 'bukti.user_id')
            ->whereIn('bukti.user_id', $mahasiswaIds)
            ->where('bukti.status', 'pending')
            ->select(
                'bukti.id',
                'users.name',
                'users.username',
                'bukti.created_at'
            )
            ->orderBy('bukti.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dosen.dashboard', compact(
            'totalMahasiswa',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'buktiPending'
        ));
    }

    // LIST
    public function index(Request $request)
    {
        $mahasiswaIds = DB::table('dosen_mahasiswa')
            ->where('dosen_id', auth()->id())
            ->pluck('mahasiswa_id');

        if ($mahasiswaIds->isEmpty()) {
            $data = collect();
            return view('dosen.bukti.index', compact('data'));
        }

        $query = DB::table('bukti')
            ->join('users', 'users.id', '=', 'bukti.user_id')
            ->whereIn('bukti.user_id', $mahasiswaIds)
            ->select(
                'bukti.*',
                'users.name as nama_mahasiswa',
                'users.username'
            );

        if ($request->filled('status')) {
            $query->where('bukti.status', $request->status);
        } else {
            $query->where('bukti.status', 'pending');
        }

        if ($request->filled('keyword')) {
            $query->where('users.name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('bukti.created_at', [
                $request->tanggal_dari,
                $request->tanggal_sampai
            ]);
        }

        $data = $query
            ->orderBy('bukti.created_at', 'desc')
            ->get();

        return view('dosen.bukti.index', compact('data'));
    }

    // APPROVE
    public function approve($id)
    {
        $bukti = DB::table('bukti')->where('id', $id)->first();

        if (!$bukti) abort(404, 'Data tidak ditemukan');
        if ($bukti->status !== 'pending') abort(403, 'Sudah diproses');

        if (!$this->isMahasiswaBimbingan($bukti->user_id)) {
            abort(403, 'Bukan mahasiswa bimbingan Anda');
        }

        DB::table('bukti')
            ->where('id', $id)
            ->update([
                'status'     => 'approved',
                'dosen_id'   => auth()->id(),
                'updated_at' => now()
            ]);

        return back()->with('success', 'Disetujui');
    }

    // REJECT
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_dosen' => 'required|string'
        ]);

        $bukti = DB::table('bukti')->where('id', $id)->first();

        if (!$bukti) abort(404, 'Data tidak ditemukan');
        if ($bukti->status !== 'pending') abort(403, 'Sudah diproses');

        if (!$this->isMahasiswaBimbingan($bukti->user_id)) {
            abort(403, 'Bukan mahasiswa bimbingan Anda');
        }

        DB::table('bukti')
            ->where('id', $id)
            ->update([
                'status'        => 'rejected',
                'catatan_dosen' => $request->catatan_dosen,
                'dosen_id'      => auth()->id(),
                'updated_at'    => now()
            ]);

        return back()->with('success', 'Ditolak');
    }

    public function mahasiswaBimbingan()
    {
        $dosenId = auth()->id();

        $mahasiswa = DB::table('dosen_mahasiswa')
            ->join('users', 'users.id', '=', 'dosen_mahasiswa.mahasiswa_id')
            ->leftJoin('bukti', function ($join) {
                $join->on('bukti.user_id', '=', 'users.id')
                    ->where('bukti.status', 'approved');
            })
            ->leftJoin(
                'master_poin_sertifikat',
                'master_poin_sertifikat.id',
                '=',
                'bukti.master_poin_id'
            )
            ->where('dosen_mahasiswa.dosen_id', $dosenId)
            ->groupBy('users.id', 'users.name', 'users.username')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                DB::raw('COALESCE(SUM(master_poin_sertifikat.poin),0) as total_poin')
            )
            ->orderBy('users.name')
            ->get();

        return view('dosen.mahasiswa.index', compact('mahasiswa'));
    }

    public function detailMahasiswa($mahasiswaId)
    {
        $dosenId = auth()->id();

        $isBimbingan = DB::table('dosen_mahasiswa')
            ->where('dosen_id', $dosenId)
            ->where('mahasiswa_id', $mahasiswaId)
            ->exists();

        if (!$isBimbingan) abort(403);

        $mahasiswa = DB::table('users')
            ->where('id', $mahasiswaId)
            ->where('role', 'mahasiswa')
            ->first();

        if (!$mahasiswa) abort(404);

        $bukti = DB::table('bukti')
            ->leftJoin(
                'master_poin_sertifikat',
                'master_poin_sertifikat.id',
                '=',
                'bukti.master_poin_id'
            )
            ->where('bukti.user_id', $mahasiswaId)
            ->select(
                'bukti.id',
                'bukti.nama_kegiatan',
                'master_poin_sertifikat.jenis_kegiatan',
                'bukti.tanggal_kegiatan',
                'bukti.file',
                'bukti.status',
                'bukti.created_at',
                DB::raw('COALESCE(master_poin_sertifikat.poin, 0) as poin')
            )
            ->orderBy('bukti.created_at', 'desc')
            ->get();

        return view('dosen.mahasiswa.detail', compact(
            'mahasiswa',
            'bukti'
        ));
    }

    private function isMahasiswaBimbingan($mahasiswaId)
    {
        return DB::table('dosen_mahasiswa')
            ->where('dosen_id', auth()->id())
            ->where('mahasiswa_id', $mahasiswaId)
            ->exists();
    }

    public function catatanKaprodi()
    {
        $dosenId = auth()->id();

        $catatan = DB::table('catatan_kaprodi')
            ->join('users as kaprodi', 'kaprodi.id', '=', 'catatan_kaprodi.kaprodi_id')
            ->where('catatan_kaprodi.dosen_id', $dosenId)
            ->select(
                'catatan_kaprodi.catatan',
                'catatan_kaprodi.created_at',
                'kaprodi.name as nama_kaprodi'
            )
            ->orderBy('catatan_kaprodi.created_at', 'desc')
            ->get();

        return view('dosen.catatan.index', compact('catatan'));
    }
}
