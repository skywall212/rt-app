<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterWarga;

class AjaxController extends Controller
{
    /**
     * Cari warga berdasarkan nama atau NIK (untuk Ajax / Select2)
     */
    public function searchWarga(Request $request)
    {
        // Validasi input
        $request->validate([
            'search' => 'required|string|max:255',
        ]);

        $keyword = $request->input('search');

        // Query MasterWarga sesuai input
        $wargas = MasterWarga::query()
            ->where('nama', 'ILIKE', "%{$keyword}%") // PostgreSQL, case-insensitive
            ->orWhere('nik', 'ILIKE', "%{$keyword}%")
            ->get(['id', 'nama', 'nik', 'tempat_lahir', 'tgl_lahir', 'alamat']);

        // Format data untuk Select2 / JSON
        $formatted = $wargas->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => "{$item->nama} - {$item->nik}", // teks yang muncul di select
                'nama' => $item->nama,
                'nik' => $item->nik,
                'tempat_lahir' => $item->tempat_lahir,
                'tgl_lahir' => $item->tgl_lahir,
                'alamat' => $item->alamat,
            ];
        });


        return response()->json($formatted);
    }
}
