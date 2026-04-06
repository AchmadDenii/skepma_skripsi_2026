<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();

        $dosenWali = User::where('role', 'dosen_wali')
            ->orderBy('name')
            ->get();

        $relasiDosen = DB::table('dosen_mahasiswa')
            ->pluck('dosen_id', 'mahasiswa_id');

        return view('admin.users.index', compact(
            'users',
            'dosenWali',
            'relasiDosen'
        ));
    }
    public function create(){
        return view('admin.users.create');
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'role' => 'required|in:mahasiswa,dosen_wali,kaprodi,admin',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
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
            'role' => 'required|in:mahasiswa,dosen_wali,kaprodi,admin',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'role' => $request->role,
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
