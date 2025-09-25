<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterWarga;

class WargaController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->get('q');

        $warga = MasterWarga::where('nama', 'like', "%$search%")
            ->orWhere('nik', 'like', "%$search%")
            ->get();

        $formatted = $warga->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nama . ' - ' . $item->nik,
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
