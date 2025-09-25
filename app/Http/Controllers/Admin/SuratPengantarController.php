<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPengantar;
use App\Models\MasterWarga;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPengantarController extends Controller
{
    // Tampil daftar surat pengantar
    public function index(Request $request)
    {
    $perPage = $request->input('perPage', 10);
    $surat = SuratPengantar::with('warga')->latest()->simplePaginate($perPage);

    $totalSurat = SuratPengantar::count();

    return view('admin.suratpengantar.index', compact('surat', 'totalSurat'));
    }


    // Form tambah surat pengantar
    public function create()
    {
        $warga = MasterWarga::all();
        return view('admin.suratpengantar.create', compact('warga'));
    }

    // Simpan data surat pengantar baru
    public function store(Request $request)
    {
        $request->validate([
            'master_warga_id' => 'required|exists:master_warga,id',
            'nama' => 'required',
            'nik' => 'required',
            'tempat' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'status_pernikahan' => 'required',
            'kewarganegaraan' => 'required',
            'agama' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'tujuan' => 'required',
        ]);

        SuratPengantar::create($request->all());

        return redirect()->route('admin.suratpengantar.index')->with('success', 'Surat Pengantar berhasil ditambahkan.');
    }

    // Form edit surat pengantar
    public function edit(SuratPengantar $suratpengantar)
    {
        $warga = MasterWarga::all();
        return view('admin.suratpengantar.edit', compact('suratpengantar', 'warga'));
    }

    // Update data surat pengantar
    public function update(Request $request, SuratPengantar $suratpengantar)
    {
        $request->validate([
            'master_warga_id' => 'required|exists:master_warga,id',
            'nama' => 'required',
            'nik' => 'required',
            'tempat' => 'required',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'status_pernikahan' => 'required',
            'kewarganegaraan' => 'required',
            'agama' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'tujuan' => 'required',
        ]);

        $suratpengantar->update($request->all());

        return redirect()->route('admin.suratpengantar.index')->with('success', 'Surat Pengantar berhasil diperbarui.');
    }

    // Hapus surat pengantar
    public function destroy(SuratPengantar $suratpengantar)
    {
        $suratpengantar->delete();

        return redirect()->route('admin.suratpengantar.index')->with('success', 'Surat Pengantar berhasil dihapus.');
    }

    // Cetak PDF surat pengantar
    public function cetak(SuratPengantar $suratpengantar)
    {
        $pdf = Pdf::loadView('admin.suratpengantar.cetak', compact('suratpengantar'));
          //  ->setPaper('a4', 'landscape');
        return $pdf->stream('surat_pengantar_' . $suratpengantar->id . '.pdf');
    }


    

    // API AJAX untuk ambil data warga berdasarkan ID (dipakai untuk autofill form)
    public function getWargaData($id)
    {
        $warga = MasterWarga::find($id);
        if (!$warga) {
            return response()->json(['error' => 'Data warga tidak ditemukan'], 404);
        }
        return response()->json($warga);
    }
}
