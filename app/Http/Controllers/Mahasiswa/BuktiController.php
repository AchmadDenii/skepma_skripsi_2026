<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bukti;
use App\Models\MasterPoinbukti;
use Carbon\Carbon;

class BuktiController extends Controller
{
    public function index()
    {
        $data = DB::table('bukti')
            ->where('user_id', Auth::id())
            ->orderBy('tanggal_kegiatan', 'desc')
            ->get();

        return view('mahasiswa.bukti.index', compact('data'));
    }

    public function create()
    {
        $kategori = ['akademik', 'non-akademik'];
        return view('mahasiswa.upload', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan'     => 'required|string|max:255',
            'master_poin_id'    => 'required|exists:master_poin_sertifikat,id',
            'tanggal_kegiatan'  => 'required|date',
            'file'              => 'required|mimes:pdf,jpg,png|max:2048',
            'keterangan'        => 'nullable|string',
        ]);

        try {
            // ========================
            // 🔥 AMBIL DOSEN WALI (FIX UTAMA)
            // ========================
            $dosenId = DB::table('dosen_mahasiswa')
                ->where('mahasiswa_id', Auth::id())
                ->value('dosen_id');

                dd([
                    'auth_id' => Auth::id(),
                    'dosen_id' => $dosenId,
                ]);
                
            if (!$dosenId) {
                return back()->with('error', 'Dosen wali belum ditentukan');
            }

            // ========================
            // 🔥 AUTO SEMESTER
            // ========================
            $tanggal = Carbon::parse($request->tanggal_kegiatan);
            $bulan = $tanggal->month;
            $tahun = $tanggal->year;

            if ($bulan >= 8) {
                $namaSemester = 'Ganjil';
                $tahunAwal = $tahun;
                $tahunAkhir = $tahun + 1;
            } elseif ($bulan <= 1) {
                $namaSemester = 'Ganjil';
                $tahunAwal = $tahun - 1;
                $tahunAkhir = $tahun;
            } else {
                $namaSemester = 'Genap';
                $tahunAwal = $tahun - 1;
                $tahunAkhir = $tahun;
            }

            $semesterNamaFull = "$namaSemester $tahunAwal/$tahunAkhir";

            $tanggalMulai = $namaSemester == 'Ganjil'
                ? "$tahunAwal-08-01"
                : "$tahunAwal-02-01";

            $tanggalSelesai = $namaSemester == 'Ganjil'
                ? "$tahunAkhir-01-31"
                : "$tahunAkhir-07-31";

            $semester = DB::table('semester')->where('nama', $semesterNamaFull)->first();

            if (!$semester) {
                $semesterId = DB::table('semester')->insertGetId([
                    'nama' => $semesterNamaFull,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                    'bulan_mulai' => $namaSemester == 'Ganjil' ? 8 : 2,
                    'bulan_selesai' => $namaSemester == 'Ganjil' ? 1 : 7,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $semesterId = $semester->id;
            }

            // ========================
            // 🔥 MASTER POIN
            // ========================
            $masterPoin = MasterPoinbukti::findOrFail($request->master_poin_id);

            // ambil kategori dari jenis kegiatan
            $kategori = DB::table('jenis_kegiatan')
                ->where('id', $masterPoin->jenis_kegiatan_id)
                ->value('kategori');

            // ========================
            // 🔥 UPLOAD FILE
            // ========================
            $path = $request->file('file')->store('bukti', 'public');

            // ========================
            // 🔥 INSERT DATA
            // ========================
            Bukti::create([
                'user_id'          => Auth::id(),
                'dosen_id'         => $dosenId, // ✅ FIX UTAMA
                'semester_id'      => $semesterId,
                'master_poin_id'   => $masterPoin->id,
                'kategori'         => $kategori,
                'nama_kegiatan'    => $request->nama_kegiatan,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'file'             => $path,
                'status'           => 'pending',
                'keterangan'       => $request->keterangan,
            ]);

            return redirect()->back()->with('success', 'Bukti berhasil diupload');

        } catch (\Exception $e) {
            dd($e->getMessage()); // 🔥 biar kalau masih error langsung kelihatan
        }
    }

    public function getJenisKegiatan($kategori)
    {
        return DB::table('jenis_kegiatan')
            ->where('kategori', $kategori)
            ->orderBy('nama')
            ->get(['id','nama']);
    }

    public function getPeran($jenis_kegiatan_id)
    {
        return DB::table('master_poin_sertifikat')
            ->where('jenis_kegiatan_id', $jenis_kegiatan_id)
            ->select('peran')
            ->distinct()
            ->pluck('peran');
    }

    public function getTingkat($jenis_kegiatan_id, $peran)
    {
        return DB::table('master_poin_sertifikat')
            ->where('jenis_kegiatan_id', $jenis_kegiatan_id)
            ->whereRaw('LOWER(TRIM(peran)) = ?', [strtolower(trim($peran))])
            ->select('id','tingkat')
            ->get();
    }
}