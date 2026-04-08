<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPoinSertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterPoinController extends Controller
{
    public function index()
    {
        $data = DB::table('master_poin_sertifikat as m')
            ->join('jenis_kegiatan as j', 'm.jenis_kegiatan_id', '=', 'j.id')
            ->select(
                'm.id',
                'j.nama as jenis_kegiatan',
                'j.kategori as kategori',
                'm.peran',
                'm.tingkat',
                'm.kode',
                'm.poin',
                'm.aktif'
            )
            ->orderBy('j.nama')
            ->orderBy('m.peran')
            ->get();

        return view('admin.master_poin.index', compact('data'));
    }

    public function create()
    {
        $jenis = DB::table('jenis_kegiatan')->get();

        return view('admin.master_poin.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required',
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatan,id',
            'peran' => 'required',
            'tingkat' => 'nullable',
            'kode' => 'required|unique:master_poin_sertifikat,kode',
            'poin' => 'required|integer',
        ]);

        $tingkat = $request->tingkat ?? null;


        DB::table('master_poin_sertifikat')->insert([
            'jenis_kegiatan_id' => $request->jenis_kegiatan_id,
            'peran' => $request->peran,
            'tingkat' => $tingkat,
            'kode' => $request->kode,
            'poin' => $request->poin,
            'bukti' => null,
            'butuh_bukti' => 1,
            'aktif' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/master-poin')
            ->with('success', 'Data master poin ditambahkan');
    }

    public function edit($id)
    {
        $data = MasterPoinSertifikat::findOrFail($id);
        $jenis = DB::table('jenis_kegiatan')->get();

        return view('admin.master_poin.edit', compact('data', 'jenis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_kegiatan_id' => 'required|exists:jenis_kegiatan,id',
            'peran' => 'required|string|max:100',
            'tingkat' => 'nullable|in:internasional,nasional,regional,institut,fakultas,lokal,jurusan',
            'kode' => 'required|string|max:50|unique:master_poin_sertifikat,kode,' . $id,
            'poin' => 'required|integer|min:0',
            'butuh_bukti' => 'nullable|boolean',
            'aktif' => 'nullable|boolean',
        ]);

        $master = MasterPoinSertifikat::findOrFail($id);
        $master->update([
            'jenis_kegiatan_id' => $request->jenis_kegiatan_id,
            'peran' => $request->peran,
            'tingkat' => $request->tingkat,
            'kode' => $request->kode,
            'poin' => $request->poin,
            'butuh_bukti' => $request->has('butuh_bukti') ? 1 : 0,
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        return redirect()->route('admin.master-poin.index')->with('success', 'Master poin berhasil diperbarui');
    }

    public function nonaktif($id)
    {
        MasterPoinSertifikat::where('id', $id)->update([
            'aktif' => 0
        ]);

        return back()->with('success', 'Master poin berhasil dinonaktifkan');
    }

    public function aktif($id)
    {
        MasterPoinSertifikat::where('id', $id)->update([
            'aktif' => 1
        ]);

        return back()->with('success', 'Master poin berhasil diaktifkan');
    }
}
