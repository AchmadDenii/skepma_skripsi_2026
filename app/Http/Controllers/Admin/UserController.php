<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Mahasiswa;
use App\Models\DosenWali;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();

        $dosenWali = User::whereIn('role', ['dosen_wali', 'kaprodi'])
            ->orderBy('name')
            ->get();

        $relasiDosen = DB::table('mahasiswa')
            ->whereNotNull('dosen_wali_id')
            ->pluck('dosen_wali_id', 'user_id');

        $mahasiswaMap = Mahasiswa::pluck('id', 'user_id');
        $dosenWaliMap = DosenWali::pluck('id', 'user_id');
        return view('admin.users.index', compact('users', 'dosenWali', 'relasiDosen', 'mahasiswaMap', 'dosenWaliMap'));
    }
    public function create()
    {
        return view('admin.users.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => null,
            'role' => 'Admin',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $request->name,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
