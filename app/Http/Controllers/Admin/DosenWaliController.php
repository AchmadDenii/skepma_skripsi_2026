<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenWaliController extends Controller
{
    /**
     * Tampilkan halaman assign dosen wali ke mahasiswa
     */
    public function index()
    {
        $dosen = User::where('role', 'dosen_wali')
            ->orderBy('name')
            ->get();

        $mahasiswa = User::where('role', 'mahasiswa')
            ->orderBy('name')
            ->get();

        // relasi existing (untuk ditampilkan)
        $relasi = DB::table('dosen_mahasiswa')->get()
            ->keyBy('mahasiswa_id');

        return view('admin.dosen-wali.index', compact(
            'dosen',
            'mahasiswa',
            'relasi'
        ));
    }

    public function assignSingle(Request $request, $userId)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id'
        ]);

        // pastikan target adalah mahasiswa
        $mahasiswa = \App\Models\User::where('id', $userId)
            ->where('role', 'mahasiswa')
            ->firstOrFail();

        DB::table('dosen_mahasiswa')->updateOrInsert(
            ['mahasiswa_id' => $mahasiswa->id],
            [
                'dosen_id' => $request->dosen_id,
                'updated_at' => now()
            ]
        );

        return back()->with('success', 'Dosen wali berhasil ditetapkan');
    }

    /**
     * Simpan / update relasi dosen wali
     */
    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'mahasiswa' => 'required|array',
            'mahasiswa.*' => 'exists:users,id',
        ]);

        foreach ($request->mahasiswa as $mahasiswaId) {
            DB::table('dosen_mahasiswa')->updateOrInsert(
                ['mahasiswa_id' => $mahasiswaId],
                [
                    'dosen_id' => $request->dosen_id,
                    'updated_at' => now()
                ]
            );
        }

        return redirect()->back()
            ->with('success', 'Dosen wali berhasil ditetapkan');
    }
}