<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bukti;
use App\Models\MasterPoinSertifikat;
use App\Models\Mahasiswa;
use App\Models\Semester;
use Carbon\Carbon;

class BuktiController extends Controller
{
    public function index()
    {
        $data = Bukti::where('user_id', Auth::id())
            ->with('masterPoin.jenisKegiatan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.riwayat', compact('data'));
    }

    public function create()
    {
        $kategori = ['akademik', 'non-akademik'];
        $jenis = DB::table('jenis_kegiatan')->get();
        return view('mahasiswa.upload', compact('kategori', 'jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatan,id',
            'peran'             => 'required|string',
            'tingkat'           => 'nullable|string',
            'tanggal_kegiatan'  => 'required|date',
            'file'              => 'required|mimes:pdf,jpg,png|max:2048',
            'keterangan'        => 'nullable|string',
        ]);

        // Cari master poin
        $query = MasterPoinSertifikat::where('jenis_kegiatan_id', $request->jenis_kegiatan_id)
            ->where('peran', $request->peran);

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        } else {
            // Jika tingkat kosong, cari yang tingkatnya NULL atau string kosong
            $query->where(function ($q) {
                $q->whereNull('tingkat')->orWhere('tingkat', '');
            });
        }

        $masterPoin = $query->first();

        if (!$masterPoin) {
            // Debug: tampilkan data yang dicari
            return back()->withErrors([
                'master_poin' => 'Aturan poin tidak ditemukan. Cek kombinasi Jenis Kegiatan ID: ' . $request->jenis_kegiatan_id .
                    ', Peran: ' . $request->peran .
                    ', Tingkat: ' . ($request->tingkat ?: '(kosong)')
            ])->withInput();
        }

        // Ambil data mahasiswa
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first();
        if (!$mahasiswa || !$mahasiswa->dosen_wali_id) {
            return back()->with('error', 'Dosen wali belum ditentukan. Silakan hubungi admin.');
        }

        // Cari record dosen_wali berdasarkan user_id
        $dosenWali = \App\Models\DosenWali::where('user_id', $mahasiswa->dosen_wali_id)->first();
        if (!$dosenWali) {
            return back()->with('error', 'Data dosen wali tidak valid.');
        }
        $dosenId = $dosenWali->id;

        // Tentukan semester
        $semester = $this->getSemesterFromDate($request->tanggal_kegiatan);
        if (!$semester) {
            $semester = $this->createSemester($request->tanggal_kegiatan);
        }

        $file = $request->file('file');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('bukti', $filename, 'public');

        // Simpan bukti
        Bukti::create([
            'user_id'          => Auth::id(),
            'semester_id'      => $semester->id,
            'dosen_id'         => $dosenId,
            'master_poin_id'   => $masterPoin->id,
            'file'             => $path,
            'status'           => 'pending',
            'keterangan'       => $request->keterangan ?: "Kegiatan ID: {$request->jenis_kegiatan_id}, Peran: {$request->peran}, Tanggal: {$request->tanggal_kegiatan}",
        ]);

        return redirect()->route('mahasiswa.bukti.index')->with('success', 'Bukti berhasil diupload.');
    }

    private function getSemesterFromDate($date)
    {
        $carbon = Carbon::parse($date);
        $bulan = $carbon->month;
        $tahun = $carbon->year;

        if ($bulan >= 8) {
            $nama = "Ganjil " . $tahun . "/" . ($tahun + 1);
        } elseif ($bulan <= 1) {
            $nama = "Ganjil " . ($tahun - 1) . "/" . $tahun;
        } else {
            $nama = "Genap " . ($tahun - 1) . "/" . $tahun;
        }

        return Semester::where('nama', $nama)->first();
    }

    private function createSemester($date)
    {
        $carbon = Carbon::parse($date);
        $bulan = $carbon->month;
        $tahun = $carbon->year;

        if ($bulan >= 8) {
            $nama = "Ganjil " . $tahun . "/" . ($tahun + 1);
            $mulai = "$tahun-08-01";
            $selesai = ($tahun + 1) . "-01-31";
            $bulanMulai = 8;
            $bulanSelesai = 1;
        } elseif ($bulan <= 1) {
            $nama = "Ganjil " . ($tahun - 1) . "/" . $tahun;
            $mulai = ($tahun - 1) . "-08-01";
            $selesai = "$tahun-01-31";
            $bulanMulai = 8;
            $bulanSelesai = 1;
        } else {
            $nama = "Genap " . ($tahun - 1) . "/" . $tahun;
            $mulai = ($tahun - 1) . "-02-01";
            $selesai = "$tahun-07-31";
            $bulanMulai = 2;
            $bulanSelesai = 7;
        }

        return Semester::create([
            'nama' => $nama,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'bulan_mulai' => $bulanMulai,
            'bulan_selesai' => $bulanSelesai,
        ]);
    }

    // API untuk dropdown dinamis
    public function getJenisKegiatan($kategori)
    {
        $data = DB::table('jenis_kegiatan')
            ->where('kategori', $kategori)
            ->orderBy('nama')
            ->get(['id', 'nama']);
        return response()->json($data);
    }

    public function getPeran($jenis_kegiatan_id)
    {
        $peran = MasterPoinSertifikat::where('jenis_kegiatan_id', $jenis_kegiatan_id)
            ->select('peran')
            ->distinct()
            ->pluck('peran');
        return response()->json($peran);
    }

    public function getTingkat($jenis_kegiatan_id, $peran)
    {
        $tingkat = MasterPoinSertifikat::where('jenis_kegiatan_id', $jenis_kegiatan_id)
            ->where('peran', $peran)
            ->select('id', 'tingkat')
            ->get();
        return response()->json($tingkat);
    }
}
