<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DosenBuktiController extends Controller
{

public function index()
{
    $dosenId = Auth::id();

    $data = DB::table('bukti')
        ->join('users','bukti.user_id','=','users.id')
        ->join('dosen_mahasiswa','users.id','=','dosen_mahasiswa.mahasiswa_id')
        ->where('dosen_mahasiswa.dosen_id',$dosenId)
        ->select(
            'bukti.*',
            'users.name',
            'users.username'
        )
        ->orderBy('bukti.created_at','desc')
        ->get();

    return view('dosen.bukti.index',compact('data'));
}

public function approve($id)
{
    DB::table('bukti')
        ->where('id',$id)
        ->update([
            'status'=>'approved',
            'dosen_id'=>Auth::id()
        ]);

    return back()->with('success','Bukti disetujui');
}

public function reject(Request $request,$id)
{
    DB::table('bukti')
        ->where('id',$id)
        ->update([
            'status'=>'rejected',
            'catatan_dosen'=>$request->catatan,
            'dosen_id'=>Auth::id()
        ]);

    return back()->with('success','Bukti ditolak');
}

}
