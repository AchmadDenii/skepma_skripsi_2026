<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MasterPoinSertifikat;
use App\Models\Sertifikat;
use Barryvdh\DomPDF\PDF as DomPDFPDF;


class SertifikatController extends Controller
{
    public function index()
    {
        $data = DB::table('sertifikat')
            ->where('user_id', Auth::id())
            ->orderBy('tanggal_kegiatan', 'desc')
            ->get();

        return view('mahasiswa.sertifikat.index', compact('data'));
    }

    public function create()
    {
        // $kategori = DB::table('master_poin_sertifikat')
            // ->select('kategori')
            // ->distinct()
            // ->orderBy('kategori')
            // ->pluck('kategori');
        $kategori = ['akademik', 'non-akademik'];
        return view('mahasiswa.upload', compact('kategori'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_kegiatan'     => 'required|string|max:255',
        'kategori'          => 'required|string',
        'master_poin_id'    => 'required|exists:master_poin_sertifikat,id',
        'tanggal_kegiatan'  => 'required|date',
        'file'              => 'required|mimes:pdf,jpg,png|max:2048',
        'keterangan'        => 'nullable|string',
    ]);

    $path = $request->file('file')->store('sertifikat', 'public');

    Sertifikat::create([
        'user_id'          => auth()->id(),
        'master_poin_id'   => $request->master_poin_id,
        'kategori'         => $request->kategori, // cache tampilan (MODE A)
        'nama_kegiatan'    => $request->nama_kegiatan,
        'tanggal_kegiatan' => $request->tanggal_kegiatan,
        'file'             => $path,
        'status'           => 'pending',
        'keterangan'       => $request->keterangan,
    ]);

    return redirect()
        ->back()
        ->with('success', 'Sertifikat berhasil diupload');
    }


    public function transkripPdf()
    {
        $user = auth()->user();
        
        $data = DB::table('sertifikat')
            ->join('master_poin_sertifikat','master_poin_sertifikat.id','=','sertifikat.master_poin_id')
            ->where('sertifikat.user_id', auth()->id())
            ->where('sertifikat.status','approved')
            ->select(
                'sertifikat.nama_kegiatan',
                'master_poin_sertifikat.kategori',
                'master_poin_sertifikat.jenis_kegiatan',
                'master_poin_sertifikat.peran',
                'master_poin_sertifikat.tingkat',
                'master_poin_sertifikat.poin'
            )
            ->get();

        $total = $data->sum('poin');
        return view('mahasiswa.transkrip.pdf', compact('user','data','total'));

        $pdf = PDF::loadView('mahasiswa.transkrip.pdf', compact('user','data','total'));
        return $pdf->download('transkrip_poin.pdf');

    }
    

    
    public function getMasterPoinByKategori($kategori)
    {
        return MasterPoinSertifikat::where('kelompok_kegiatan', $kategori)
            ->orderBy('tingkat')
            ->orderBy('peran')
            ->get(['id', 'peran', 'tingkat']);
    }

    public function getJenisKegiatan($kategori)
    {
        return DB::table('master_poin_sertifikat')
            ->where('kategori', $kategori)
            ->select('jenis_kegiatan')
            ->distinct()
            ->orderBy('jenis_kegiatan')
            ->pluck('jenis_kegiatan');
    }

    public function getPeran($kategori, $jenis)
    {
        return DB::table('master_poin_sertifikat')
            ->where('kategori', $kategori)
            ->where('jenis_kegiatan', $jenis)
            ->select('peran')
            ->distinct()
            ->orderBy('peran')
            ->pluck('peran');
    }

    public function getTingkat($kategori, $jenis, $peran)
    {
        return DB::table('master_poin_sertifikat')
            ->where('kategori', $kategori)
            ->where('jenis_kegiatan', $jenis)
            ->where('peran', $peran)
            ->select('id','tingkat')
            ->orderBy('tingkat')
            ->get();
    }

// public function filterMasterPoin(Request $request)
    // {
    //     $query = MasterPoinSertifikat::query();
    //     if ($request->kategori) {
    //         $query->where('kategori', $request->kategori);
    //     }
    //     if ($request->jenis_kegiatan) {
    //         $query->where('jenis_kegiatan', $request->jenis_kegiatan);
    //     }
    //     if ($request->peran) {
    //         $query->where('peran', $request->peran);
    //     }
    //     return response()->json([
    //         'jenis_kegiatan' => $query->clone()
    //             ->select('jenis_kegiatan')
    //             ->distinct()
    //             ->pluck('jenis_kegiatan'),
    //         'peran' => $query->clone()
    //             ->select('peran')
    //             ->distinct()
    //             ->pluck('peran'),
    //         'data' => $query->get(['id','tingkat','poin'])
    //     ]);
    // }
}