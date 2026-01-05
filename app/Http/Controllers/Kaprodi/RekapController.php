<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index()
    {
        $target = 1500; // target poin sesuai proposal

        $data = DB::table('users')
            ->where('users.role', 'mahasiswa')
            ->leftJoin('sertifikat', function ($join) {
                $join->on('users.id', '=', 'sertifikat.user_id')
                     ->where('sertifikat.status', 'approved');
            })
            ->leftJoin(
                'master_poin_sertifikat',
                'master_poin_sertifikat.id',
                '=',
                'sertifikat.master_poin_id'
            )
            ->select(
                'users.id',
                'users.name',
                'users.username',
                DB::raw('COALESCE(SUM(master_poin_sertifikat.poin), 0) as total_poin')
            )
            ->groupBy('users.id', 'users.name', 'users.username')
            ->orderByDesc('total_poin')
            ->get();

        return view('kaprodi.rekap', compact('data', 'target'));
    }
}