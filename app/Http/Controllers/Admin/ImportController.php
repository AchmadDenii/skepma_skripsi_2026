<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MasterPoinImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;

class ImportController extends Controller
{
    public function importMasterPoin(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            Excel::import(new MasterPoinImport, $request->file('file'));
            return back()->with('success', 'Import master poin berhasil');
        } catch (ValidationException $e) {
            $errors = [];
            foreach ($e->failures() as $failure) {
                $errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }
            return back()->with('errors', $errors);
        } catch (\Exception $e) {
            return back()->with('errors', [$e->getMessage()]);
        }
    }
    public function masterPoinForm()
    {
        return view('admin.import.master_poin');
    }
}
