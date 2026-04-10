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
        $dosenId = auth()->id(); // ID user dosen wali

        // Ambil semua user_id mahasiswa yang dibimbing oleh dosen wali ini
        $mahasiswaIds = DB::table('mahasiswa')
            ->where('dosen_wali_id', $dosenId)
            ->pluck('user_id'); // karena bukti.user_id mengacu ke users.id mahasiswa

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
        // Ambil semua user_id mahasiswa yang dibimbing oleh dosen wali yang sedang login
        $mahasiswaIds = DB::table('mahasiswa')
            ->where('dosen_wali_id', auth()->id())
            ->pluck('user_id'); // karena bukti.user_id adalah user_id mahasiswa

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

        // Filter status (default pending jika tidak ada filter)
        if ($request->filled('status')) {
            $query->where('bukti.status', $request->status);
        } else {
            $query->where('bukti.status', 'pending');
        }

        // Filter keyword (nama mahasiswa)
        if ($request->filled('keyword')) {
            $query->where('users.name', 'like', '%' . $request->keyword . '%');
        }

        // Filter rentang tanggal (created_at)
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

    public function show($id)
    {
        $bukti = DB::table('bukti')
            ->join('users', 'users.id', '=', 'bukti.user_id')
            ->leftJoin('master_poin_sertifikat', 'master_poin_sertifikat.id', '=', 'bukti.master_poin_id')
            ->where('bukti.id', $id)
            ->select(
                'bukti.*',
                'users.name as nama_mahasiswa',
                'users.username',
                'master_poin_sertifikat.poin'
            )
            ->first();

        if (!$bukti) abort(404, 'Data tidak ditemukan');

        // Cek apakah mahasiswa ini bimbingan dosen yang login
        $isBimbingan = DB::table('mahasiswa')
            ->where('user_id', $bukti->user_id)
            ->where('dosen_wali_id', auth()->id())
            ->exists();

        if (!$isBimbingan) abort(403, 'Bukan mahasiswa bimbingan Anda');

        return view('dosen.bukti.show', compact('bukti'));
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

        // Cari id dosen_wali berdasarkan user_id yang login
        $dosenWali = DB::table('dosen_wali')->where('user_id', auth()->id())->first();
        if (!$dosenWali) {
            return back()->with('error', 'Data dosen wali tidak valid.');
        }

        DB::table('bukti')
            ->where('id', $id)
            ->update([
                'status'     => 'approved',
                'dosen_id'   => $dosenWali->id, // ✅ gunakan id dari tabel dosen_wali
                'updated_at' => now()
            ]);

        return back()->with('success', 'Bukti berhasil disetujui');
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

        $dosenWali = DB::table('dosen_wali')->where('user_id', auth()->id())->first();
        if (!$dosenWali) {
            return back()->with('error', 'Data dosen wali tidak valid.');
        }

        DB::table('bukti')
            ->where('id', $id)
            ->update([
                'status'        => 'rejected',
                'dosen_id'      => $dosenWali->id,
                'catatan_dosen' => $request->catatan_dosen,
                'updated_at'    => now()
            ]);

        return back()->with('success', 'Bukti ditolak');
    }

    public function mahasiswaBimbingan()
    {
        $dosenId = auth()->id(); // ID user dosen wali

        $mahasiswa = DB::table('mahasiswa')
            ->join('users', 'users.id', '=', 'mahasiswa.user_id')
            ->leftJoin('bukti', function ($join) {
                $join->on('bukti.user_id', '=', 'users.id')
                    ->where('bukti.status', 'approved');
            })
            ->leftJoin('master_poin_sertifikat', 'master_poin_sertifikat.id', '=', 'bukti.master_poin_id')
            ->where('mahasiswa.dosen_wali_id', $dosenId)
            ->groupBy('users.id', 'users.name', 'users.username')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                DB::raw('COALESCE(SUM(master_poin_sertifikat.poin), 0) as total_poin')
            )
            ->orderBy('users.name')
            ->get();

        return view('dosen.mahasiswa.index', compact('mahasiswa'));
    }

    public function detailMahasiswa($mahasiswaId)
    {
        $dosenId = auth()->id();

        // Cek apakah mahasiswa dengan user_id = $mahasiswaId berada dalam bimbingan dosen ini
        $isBimbingan = DB::table('mahasiswa')
            ->where('user_id', $mahasiswaId)
            ->where('dosen_wali_id', $dosenId)
            ->exists();

        if (!$isBimbingan) {
            abort(403, 'Anda tidak memiliki akses ke data mahasiswa ini.');
        }

        // Ambil data user mahasiswa
        $mahasiswa = DB::table('users')
            ->where('id', $mahasiswaId)
            ->where('role', 'mahasiswa')
            ->first();

        if (!$mahasiswa) {
            abort(404, 'Mahasiswa tidak ditemukan.');
        }

        // Ambil data bukti mahasiswa
        $bukti = DB::table('bukti')
            ->leftJoin('master_poin_sertifikat', 'master_poin_sertifikat.id', '=', 'bukti.master_poin_id')
            ->leftJoin('jenis_kegiatan', 'jenis_kegiatan.id', '=', 'master_poin_sertifikat.jenis_kegiatan_id')
            ->where('bukti.user_id', $mahasiswaId)
            ->select(
                'bukti.id',
                DB::raw('COALESCE(bukti.keterangan, "-") as nama_kegiatan'),
                'jenis_kegiatan.nama as jenis_kegiatan',
                'bukti.created_at as tanggal_kegiatan',
                'bukti.file',
                'bukti.status',
                'bukti.created_at',
                DB::raw('COALESCE(master_poin_sertifikat.poin, 0) as poin')
            )
            ->orderBy('bukti.created_at', 'desc')
            ->get();

        return view('dosen.mahasiswa.detail', compact('mahasiswa', 'bukti'));
    }

    private function isMahasiswaBimbingan($userId)
    {
        return DB::table('mahasiswa')
            ->where('user_id', $userId)
            ->where('dosen_wali_id', auth()->id())
            ->exists();
    }

    public function catatanKaprodi()
    {
        $dosenWali = DB::table('dosen_wali')->where('user_id', auth()->id())->first();

        if (!$dosenWali) {
            $catatan = collect();
            return view('dosen.catatan.index', compact('catatan'));
        }

        $dosenId = $dosenWali->id;

        $catatan = DB::table('catatan_kaprodi')
            ->join('users as kaprodi', 'kaprodi.id', '=', 'catatan_kaprodi.kaprodi_id')
            ->where('catatan_kaprodi.dosen_id', $dosenId)
            ->select(
                'catatan_kaprodi.catatan',
                'catatan_kaprodi.created_at',
                'kaprodi.name as nama_kaprodi'
            )
            ->orderByDesc('catatan_kaprodi.created_at')
            ->get();

        return view('dosen.catatan.index', compact('catatan'));
    }
}
