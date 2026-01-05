<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SertifikatController extends Controller
{
    // List sertifikat pending
    public function index()
    {
        $data = DB::table('sertifikat')
            ->join('users', 'users.id', '=', 'sertifikat.user_id')
            ->select(
                'sertifikat.*',
                'users.name as nama_mahasiswa',
                'users.username'
            )
            ->where('sertifikat.status', 'pending')
            ->orderBy('sertifikat.created_at', 'desc')
            ->get();

        return view('dosen.sertifikat.index', compact('data'));
    }

    // APPROVE
    public function approve($id)
    {
        $sertifikat = DB::table('sertifikat')
            ->join('master_poin_sertifikat','master_poin_sertifikat.id','=','sertifikat.master_poin_id')
            ->where('sertifikat.id', $id)
            ->select('sertifikat.id','master_poin_sertifikat.poin')
            ->first();

        DB::table('sertifikat')
            ->where('id', $id)
            ->update([
                'status'    => 'approved',
                'dosen_id'  => auth()->id(),
                'updated_at'=> now()
            ]);
        return back()->with('success','Sertifikat disetujui');
    }

    // REJECT
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_dosen' => 'required|string'
        ]);

        DB::table('sertifikat')
            ->where('id', $id)
            ->update([
                'status'         => 'rejected',
                'catatan_dosen'  => $request->catatan_dosen,
                'dosen_id'       => auth()->id(),
                'updated_at'     => now()
            ]);

        return back()->with('success','Sertifikat ditolak');
    }
}