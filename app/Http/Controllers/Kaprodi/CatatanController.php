<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CatatanController extends Controller
{
    public function index()
    {
        $catatan = DB::table('catatan_kaprodi as c')
            ->join('dosen_wali as dw', 'dw.id', '=', 'c.dosen_id')
            ->join('users as dosen', 'dosen.id', '=', 'dw.user_id')
            ->join('users as kaprodi', 'kaprodi.id', '=', 'c.kaprodi_id')
            ->select(
                'c.id',
                'dosen.name as nama_dosen',
                'kaprodi.name as nama_kaprodi',
                'c.catatan',
                'c.created_at'
            )
            ->orderByDesc('c.created_at')
            ->paginate(15);

        return view('kaprodi.catatan.index', compact('catatan'));
    }

    public function create()
    {
        $dosen = DB::table('users')
            ->where('role', 'dosen_wali')
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return view('kaprodi.catatan.create', compact('dosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'catatan'  => 'required|string|max:1000',
        ]);

        $dosenWali = DB::table('dosen_wali')->where('user_id', $request->dosen_id)->first();
        if (!$dosenWali) {
            return back()->withErrors(['dosen_id' => 'Dosen wali tidak valid.'])->withInput();
        }

        DB::table('catatan_kaprodi')->insert([
            'kaprodi_id' => auth()->id(),
            'dosen_id'   => $dosenWali->id,
            'catatan'    => $request->catatan,
            'created_at' => now(),
        ]);

        return redirect()->route('kaprodi.catatan.index')
            ->with('success', 'Catatan berhasil dikirim ke dosen wali');
    }
}
