<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuktiController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('bukti as b')
            ->join('users as u','b.user_id','=','u.id')
            ->join('semester as s','b.semester_id','=','s.id')
            ->join('master_poin_sertifikat as mp','b.master_poin_id','=','mp.id')
            ->select(
                'b.id',
                'u.name',
                'u.username',
                'b.nama_kegiatan',
                's.nama as semester',
                'mp.poin',
                'b.status',
                'b.tanggal_kegiatan',
                'b.file'
            );
        //filter search mahasiswa
        if($request->search){
            $query->where(function($q) use ($request){
                $q->where('u.name','like','%'.$request->search.'%')
                  ->orWhere('u.username','like','%'.$request->search.'%');
            });
        }

        if($request->status){
            $query->where('b.status',$request->status);
        }

        if($request->semester){
            $query->where('b.semester_id',$request->semester);
        }

        $bukti = $query->orderBy('b.created_at','desc')
               ->paginate(10)
               ->appends(request()->query());

        $semester = DB::table('semester')->get();

        return view('admin.bukti.index', compact('bukti','semester'));
    }
}