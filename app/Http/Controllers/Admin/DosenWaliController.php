<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DosenWali;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DosenWaliController extends Controller
{
    /**
     * Daftar semua dosen wali (dengan user dan jumlah mahasiswa bimbingan)
     */
    public function index()
    {
        $dosenWali = DosenWali::with('user')
            ->withCount('mahasiswa')
            ->get();

        return view('admin.dosen-wali.index', compact('dosenWali'));
    }

    /**
     * Form tambah dosen wali
     */
    public function create()
    {
        $kaprodiExists = User::where('role', 'kaprodi')->exists();
        return view('admin.dosen-wali.create', compact('kaprodiExists'));
    }

    /**
     * Simpan dosen wali baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'password' => 'required|min:6',
            'nip' => 'required|string|unique:dosen_wali,nip',
            'email' => 'nullable|email',
            'is_kaprodi' => 'nullable|boolean',
        ]);

        // Jika user ingin menjadikan kaprodi, pastikan belum ada kaprodi lain
        $isKaprodi = $request->boolean('is_kaprodi');
        if ($isKaprodi && User::where('role', 'kaprodi')->exists()) {
            return back()->withErrors(['is_kaprodi' => 'Hanya boleh ada satu Kaprodi. Nonaktifkan Kaprodi yang ada terlebih dahulu.'])
                ->withInput();
        }

        $role = $isKaprodi ? 'kaprodi' : 'dosen_wali';

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $role,
            'password' => Hash::make($request->password),
        ]);

        DosenWali::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Dosen wali berhasil ditambahkan' . ($isKaprodi ? ' sebagai Kaprodi.' : '.'));
    }

    /**
     * Form edit dosen wali
     */
    public function edit($id)
    {
        $dosenWali = DosenWali::with('user')->findOrFail($id);
        $kaprodiExists = User::where('role', 'kaprodi')
            ->where('id', '!=', $dosenWali->user->id)
            ->exists();
        return view('admin.dosen-wali.edit', compact('dosenWali', 'kaprodiExists'));
    }

    /**
     * Update dosen wali
     */
    public function update(Request $request, $id)
    {
        $dosenWali = DosenWali::findOrFail($id);
        $user = $dosenWali->user;

        $request->validate([
            'name'      => 'required|string|max:255',
            'nip'       => 'required|string|unique:dosen_wali,nip,' . $dosenWali->id,
            'password'  => 'nullable|min:6',
            'email'     => 'nullable|email|unique:users,email,' . $user->id,
            'is_kaprodi' => 'nullable|boolean',
        ]);

        $isKaprodi = $request->boolean('is_kaprodi');

        // Jika ingin dijadikan kaprodi, pastikan belum ada kaprodi lain (kecuali dirinya sendiri)
        if ($isKaprodi && $user->role !== 'kaprodi') {
            $existingKaprodi = User::where('role', 'kaprodi')
                ->where('id', '!=', $user->id)
                ->exists();
            if ($existingKaprodi) {
                return back()->withErrors(['is_kaprodi' => 'Hanya boleh ada satu Kaprodi. Nonaktifkan Kaprodi yang ada terlebih dahulu.'])
                    ->withInput();
            }
        }

        // Tentukan role baru
        $newRole = $isKaprodi ? 'kaprodi' : 'dosen_wali';

        // Siapkan data untuk update user
        $userData = [
            'name'  => $request->name,
            'role'  => $newRole,
            'email' => $request->email, // update email
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($userData);

        // Update nip di tabel dosen_wali
        $dosenWali->update([
            'nip' => $request->nip,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Dosen wali berhasil diperbarui' . ($isKaprodi ? ' sebagai Kaprodi.' : '.'));
    }

    /**
     * Hapus dosen wali (beserta user-nya)
     */
    public function destroy($id)
    {
        $dosenWali = DosenWali::findOrFail($id);
        $user = $dosenWali->user;
        $dosenWali->delete();
        $user->delete();

        return redirect()->route('admin.dosen-wali.index')
            ->with('success', 'Dosen wali berhasil dihapus');
    }

    // ========== FITUR ASSIGN DOSEN WALI KE MAHASISWA ==========

    /**
     * Halaman assign dosen wali ke mahasiswa
     */
    public function assignIndex()
    {
        $dosen = User::where('role', 'dosen_wali')->orderBy('name')->get();
        $mahasiswa = Mahasiswa::with('user')->get(); // semua mahasiswa

        return view('admin.dosen-wali.index', compact('dosen', 'mahasiswa'));
    }

    /**
     * Assign dosen wali ke satu mahasiswa (via tombol di daftar)
     */
    public function assignSingle(Request $request, $userId)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id'
        ]);

        // Pastikan dosen yang dipilih memiliki role dosen_wali
        $dosen = User::where('id', $request->dosen_id)
            ->whereIn('role', ['dosen_wali', 'kaprodi'])
            ->firstOrFail();

        // Cari mahasiswa berdasarkan user_id (bukan id mahasiswa)
        $mahasiswa = Mahasiswa::where('user_id', $userId)->firstOrFail();

        $mahasiswa->dosen_wali_id = $dosen->id;
        $mahasiswa->save();

        return back()->with('success', 'Dosen wali berhasil ditetapkan untuk mahasiswa ini');
    }

    /**
     * Assign dosen wali ke banyak mahasiswa sekaligus (bulk assign)
     */
    public function assignBulk(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'mahasiswa_ids' => 'required|array',
            'mahasiswa_ids.*' => 'exists:mahasiswa,id',
        ]);

        $dosen = User::where('id', $request->dosen_id)
            ->wherein('role', ['dosen_wali', 'kaprodi'])
            ->firstOrFail();

        Mahasiswa::whereIn('id', $request->mahasiswa_ids)
            ->update(['dosen_wali_id' => $dosen->id]);

        return redirect()->back()
            ->with('success', 'Dosen wali berhasil ditetapkan untuk ' . count($request->mahasiswa_ids) . ' mahasiswa');
    }
}
