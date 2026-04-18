<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bukti;
use Barryvdh\DomPDF\Facade\Pdf;

class MahasiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // total poin yang disetujui
        $totalPoin = DB::table('bukti')
            ->join('master_poin_sertifikat', 'bukti.master_poin_id', '=', 'master_poin_sertifikat.id')
            ->where('bukti.user_id', $user->id)
            ->where('bukti.status', 'approved')
            ->sum('master_poin_sertifikat.poin');

        // target sistem
        $targetKelulusan = 1500;

        // asumsi kuliah 8 semester
        $targetPerSemester = $targetKelulusan / 8;

        // progress mahasiswa
        $progress = ($totalPoin / $targetKelulusan) * 100;

        // poin yang masih kurang
        $poinKurang = max(0, $targetKelulusan - $totalPoin);

        // bukti terbaru
        $buktiTerbaru = Bukti::where('user_id', auth()->id())
            ->with(['masterPoin.jenisKegiatan']) // eager loading relasi
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();


        return view('mahasiswa.dashboard', [
            'mahasiswa'        => $user,
            'totalPoin'        => $totalPoin,
            'targetKelulusan'  => $targetKelulusan,
            'targetPerSemester' => $targetPerSemester,
            'poinKurang'       => $poinKurang,
            'progress'         => $progress,
            'buktiTerbaru'     => $buktiTerbaru,
        ]);
    }

    public function create()
    {
        $dosenWali = User::where('role', 'dosen_wali')->orderBy('name')->get();
        return view('mahasiswa.create', compact('dosenWali'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'password' => 'required|min:6',
            'npm' => 'required|string|unique:mahasiswa,npm',
            'email' => 'nullable|email',
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|integer|min:1900|max:' . date('Y'),
            'semester' => 'required|integer|min:1|max:14',
            'ipk' => 'nullable|numeric|min:0|max:4',
        ]);


        // Buat user dengan role mahasiswa
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => 'mahasiswa',
            'password' => Hash::make($request->password),
        ]);

        // Buat data mahasiswa
        Mahasiswa::create([
            'user_id' => $user->id,
            'npm' => $request->npm,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
            'semester' => $request->semester,
            'ipk' => $request->ipk,
            'dosen_wali_id' => null,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        $dosenWali = User::where('role', 'dosen_wali')->orderBy('name')->get();
        return view('mahasiswa.edit', compact('mahasiswa', 'dosenWali'));
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $user = $mahasiswa->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'npm' => 'required|string|unique:mahasiswa,npm,' . $mahasiswa->id,
            'email' => 'nullable|email',
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|integer|min:1900|max:' . date('Y'),
            'semester' => 'required|integer|min:1|max:14',
            'ipk' => 'nullable|numeric|min:0|max:4',
            'password' => 'nullable|min:6',
        ]);

        // Update user
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $user->update($userData);

        // Update mahasiswa
        $mahasiswa->update([
            'npm' => $request->npm,
            'prodi' => $request->prodi,
            'angkatan' => $request->angkatan,
            'semester' => $request->semester,
            'ipk' => $request->ipk,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Mahasiswa berhasil diperbarui');
    }
    public function exportPdf($id)
    {
        $mahasiswa = User::with(['mahasiswa.dosenWali'])->findOrFail($id);

        $bukti = Bukti::with(['masterPoin.jenisKegiatan'])
            ->where('user_id', $id)
            ->where('status', 'approved')
            ->get();

        $totalPoin = $bukti->sum(fn($item) => $item->masterPoin->poin ?? 0);

        $dosenWali = $mahasiswa->mahasiswa->dosenWali ?? null;

        $data = [
            'mahasiswa'  => $mahasiswa,
            'bukti'      => $bukti,
            'totalPoin'  => $totalPoin,
            'dosenWali'  => $dosenWali,
        ];

        $pdf = Pdf::loadView('dosen.pdf.mahasiswa_detail', $data);
        return $pdf->download('Rekap_SKEPMA_' . ($mahasiswa->mahasiswa->npm ?? $mahasiswa->id) . '.pdf');
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $user = $mahasiswa->user;
        $mahasiswa->delete();
        $user->delete(); // hapus user juga

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil dihapus');
    }
}
