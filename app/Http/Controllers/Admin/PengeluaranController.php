<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pengeluarans = Pengeluaran::when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('jenis', 'like', "%{$search}%");
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('admin.pengeluaran.index', compact('pengeluarans'));
    }

    public function create()
    {
        return view('admin.pengeluaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'jenis'       => 'required|string|max:255',
            'tanggal'     => 'required|date',
            'jumlah'      => 'required|numeric',
            'keterangan'  => 'nullable|string',
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama', 'jenis', 'tanggal', 'jumlah', 'keterangan']);

        if ($request->hasFile('bukti_bayar')) {
            $data['bukti_bayar'] = $request->file('bukti_bayar')->store('pengeluarans', 'public');
        }

        Pengeluaran::create($data);

        return redirect()->route('admin.pengeluaran.index')
                         ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        return view('admin.pengeluaran.edit', compact('pengeluaran'));
    }


    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'jenis'       => 'required|string|max:255',
            'tanggal'     => 'required|date',
            'jumlah'      => 'required|numeric',
            'keterangan'  => 'nullable|string',
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama', 'jenis', 'tanggal', 'jumlah', 'keterangan']);

        if ($request->hasFile('bukti_bayar')) {
            if ($pengeluaran->bukti_bayar) {
                Storage::disk('public')->delete($pengeluaran->bukti_bayar);
            }
            $data['bukti_bayar'] = $request->file('bukti_bayar')->store('pengeluarans', 'public');
        }

        $pengeluaran->update($data);

        return redirect()->route('admin.pengeluaran.index')
                         ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->bukti_bayar) {
            Storage::disk('public')->delete($pengeluaran->bukti_bayar);
        }

        $pengeluaran->delete();

        return redirect()->route('admin.pengeluaran.index')
                         ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
