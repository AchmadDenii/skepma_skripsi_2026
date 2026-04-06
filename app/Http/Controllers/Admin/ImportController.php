<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MasterPoinImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importMasterPoin(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new MasterPoinImport, $request->file('file'));

            return back()->with('success', 'Import berhasil');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function masterPoinForm()
    {
        return view('admin.import.master_poin');
    }
}