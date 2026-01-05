<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Total poin hanya dari sertifikat APPROVED
        $totalPoin = DB::table('sertifikat')
        ->join(
            'master_poin_sertifikat',
            'sertifikat.master_poin_id',
            '=',
            'master_poin_sertifikat.id'
        )
        ->where('sertifikat.user_id', $user->id)
        ->where('sertifikat.status', 'approved')
        ->sum('master_poin_sertifikat.poin');

        // Sertifikat pending
        $pending = DB::table('sertifikat')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Target poin (sesuai proposal, statis dulu)
        $target = 1500;

        return view('mahasiswa.dashboard', compact(
            'totalPoin',
            'pending',
            'target'
        ));
    }
}