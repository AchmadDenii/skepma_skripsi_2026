<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = DB::table('users')
            ->where('role','mahasiswa')
            ->count();

        $totalBukti = DB::table('bukti')->count();

        $pending = DB::table('bukti')
            ->where('status','pending')
            ->count();

        $masterPoin = DB::table('master_poin_sertifikat')
            ->count();

        return view('admin.dashboard', compact(
            'totalMahasiswa',
            'totalBukti',
            'pending',
            'masterPoin'
        ));
    }
}