<?php

namespace App\Http\Controllers;

use App\Imports\GamesImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class GameImportController extends Controller
{
    /**
     * Tampilkan halaman upload Excel.
     */
    public function index()
    {
        return view('import.index');
    }

    /**
     * Proses upload dan import file Excel.
     */
    public function store(Request $request)
    {
        // Validasi file
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240', // max 10MB
            ],
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes' => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $import = new GamesImport;
            Excel::import($import, $request->file('file'));

            $message = "Berhasil mengimport {$import->imported} game.";
            $failures = $import->failures;

            return redirect()->route('import.index')
                ->with('success', $message)
                ->with('failures', $failures);

        } catch (ValidationException $e) {
            return redirect()->route('import.index')
                ->with('error', 'File Excel tidak valid: '.$e->getMessage());

        } catch (\Throwable $e) {
            return redirect()->route('import.index')
                ->with('error', 'Gagal mengimport: '.$e->getMessage());
        }
    }
}
