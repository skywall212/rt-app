<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterWarga;
use Illuminate\Http\Request;

class MasterWargaController extends Controller
{
    public function index()
    {
        // 10 data per halaman, bisa diubah sesuai kebutuhan
        //$warga = MasterWarga::orderBy('alamat')->paginate(50);  
        $warga =MasterWarga::orderByRaw("
            CAST(substring(alamat from 'G([0-9]+)') AS INTEGER) ASC,
            CAST(substring(alamat from '/([0-9]+)') AS INTEGER) ASC
        ")->paginate(20);
        return view('admin.masterwarga.index', compact('warga'));
    }


    public function create()
    {
        return view('admin.masterwarga.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:master_warga,nik',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required',
        ]);

        MasterWarga::create($request->all());

        return redirect()->route('admin.masterwarga.index')->with('success', 'Warga berhasil ditambahkan.');
    }

    public function edit(MasterWarga $masterwarga)
    {
        return view('admin.masterwarga.edit', compact('masterwarga'));
    }

    public function update(Request $request, MasterWarga $masterwarga)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:master_warga,nik,' . $masterwarga->id,
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required',
        ]);

        $masterwarga->update($request->all());

        return redirect()->route('admin.masterwarga.index')->with('success', 'Warga berhasil diperbarui.');
    }

    public function destroy(MasterWarga $masterwarga)
    {
        $masterwarga->delete();
        return redirect()->route('admin.masterwarga.index')->with('success', 'Warga berhasil dihapus.');
    }
}
