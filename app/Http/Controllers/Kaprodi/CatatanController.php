<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatatanController extends Controller
{
    public function index()
    {
        $catatan = DB::table('catatan_kaprodi')
            ->join('users as dosen', 'dosen.id', '=', 'catatan_kaprodi.dosen_id')
            ->join('users as kaprodi', 'kaprodi.id', '=', 'catatan_kaprodi.kaprodi_id')
            ->select(
                'catatan_kaprodi.id',
                'dosen.name as nama_dosen',
                'kaprodi.name as nama_kaprodi',
                'catatan_kaprodi.catatan',
                'catatan_kaprodi.created_at'
            )
            ->orderByDesc('catatan_kaprodi.created_at')
            ->get();

        return view('kaprodi.catatan.index', compact('catatan'));
    }

    public function create()
    {
        $dosen = DB::table('users')
            ->where('role', 'dosen_wali')
            ->orderBy('name')
            ->get();

        return view('kaprodi.catatan.create', compact('dosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'catatan'  => 'required|string'
        ]);

        DB::table('catatan_kaprodi')->insert([
            'kaprodi_id' => auth()->id(),
            'dosen_id'   => $request->dosen_id,
            'catatan'    => $request->catatan,
            'created_at' => now()
        ]);

        return redirect()
            ->route('kaprodi.catatan.index')
            ->with('success', 'Catatan berhasil dikirim ke dosen wali');
    }
}