<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPoinSertifikat;
use Illuminate\Http\Request;

class MasterPoinController extends Controller
{
    public function index()
    {
        $data = MasterPoinSertifikat::orderBy('jenis_kegiatan')->get();
        return view('admin.master_poin.index', compact('data'));
    }

    public function create()
    {
        return view('admin.master_poin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelompok_kegiatan' => 'required',
            'tingkat' => 'nullable',
            'peran' => 'required',
            'kode' => 'required|unique:master_poin_sertifikat,kode',
            'poin' => 'required|integer',
        ]);
            MasterPoinSertifikat::create([
            'kelompok_kegiatan' => $request->kelompok_kegiatan,
            'tingkat'           => $request->tingkat,
            'peran'             => $request->peran,
            'kode'              => $request->kode,
            'poin'              => $request->poin,
        ]);
        
        return redirect('/admin/master-poin')->with('success', 'Data master poin ditambahkan');
    }
}