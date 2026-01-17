<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\MasterWarga;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('warga')->orderBy('tanggal', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('warga', function ($qw) use ($search) {
                    $qw->where('nama', 'ILIKE', "%{$search}%");
                })
                ->orWhere('jenis', 'ILIKE', "%{$search}%");
            });
        }

        $pembayarans = $query->paginate(10)->appends($request->all());

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function create()
    {
        $warga = MasterWarga::all();
        return view('admin.pembayaran.create', compact('warga'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id'         => 'required|exists:master_warga,id',
            'tanggal'          => 'required|date',
            'bulan_bayar'      => 'required|string|max:20',
            'jenis'            => 'required|in:Sampah Keamanan,Dana Sosial,Pulasara,Sumbangan',
            'peserta'          => 'nullable|integer|min:1',
            'jumlah_pulasara'  => 'nullable|numeric|min:0',
            'jumlah_general'   => 'nullable|numeric|min:0',
            'keterangan'       => 'nullable|string',
            'bukti_bayar'      => 'nullable|image|max:2048',
        ]);

       // Cegah double bayar Pulasara per bulan & tahun
        if ($validated['jenis'] === 'Pulasara') {

            $tanggal = \Carbon\Carbon::parse($validated['tanggal']);
            $tahun   = $tanggal->year;
            $bulan   = $validated['bulan_bayar'];

            $sudahBayar = Pembayaran::where('warga_id', $validated['warga_id'])
                ->where('jenis', 'Pulasara')
                ->whereYear('tanggal', $tahun)
                ->where('bulan_bayar', $bulan)
                ->exists();

            if ($sudahBayar) {
                return back()->withInput()
                    ->with('warning', "❗ Pulasara bulan {$bulan} tahun {$tahun} sudah dibayarkan.");
            }
        }


        // Cegah double bayar bulanan (SK & DS)
        if (in_array($validated['jenis'], ['Sampah Keamanan', 'Dana Sosial'])) {
            $sudahBayar = Pembayaran::where('warga_id', $validated['warga_id'])
                ->where('jenis', $validated['jenis'])
                ->where('bulan_bayar', $validated['bulan_bayar'])
                ->whereYear('tanggal', date('Y', strtotime($validated['tanggal'])))
                ->exists();

            if ($sudahBayar) {
                return back()->withInput()
                    ->with('warning', '‼️ Pembayaran untuk bulan ini sudah ada.');
            }
        }

        $jumlah = $validated['jenis'] === 'Pulasara'
            ? $validated['jumlah_pulasara']
            : $validated['jumlah_general'];

        if ($jumlah === null) {
            return back()->withInput()
                ->with('warning', 'Jumlah pembayaran wajib diisi.');
        }

        $data = [
            'warga_id'    => $validated['warga_id'],
            'tanggal'     => $validated['tanggal'],
            'bulan_bayar' => $validated['bulan_bayar'], // ✅ FIX UTAMA
            'jenis'       => $validated['jenis'],
            'peserta'     => $validated['jenis'] === 'Pulasara' ? $validated['peserta'] : null,
            'jumlah'      => $jumlah,
            'keterangan'  => $validated['keterangan'] ?? null,
        ];

        if ($request->hasFile('bukti_bayar')) {
            $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        Pembayaran::create($data);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil disimpan.');
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $warga = MasterWarga::all();
        return view('admin.pembayaran.edit', compact('pembayaran', 'warga'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'warga_id'    => 'required|exists:master_warga,id',
            'tanggal'     => 'required|date',
            'bulan_bayar' => 'required|string|max:20',
            'jenis'       => 'required|string|max:255',
            'jumlah'      => 'required|numeric|min:0',
            'peserta'     => 'nullable|integer|min:1',
            'keterangan'  => 'nullable|string',
            'bukti_bayar' => 'nullable|image|max:2048',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $data = [
            'warga_id'    => $validated['warga_id'],
            'tanggal'     => $validated['tanggal'],
            'bulan_bayar' => $validated['bulan_bayar'], // ✅ FIX
            'jenis'       => $validated['jenis'],
            'jumlah'      => $validated['jumlah'],
            'peserta'     => $validated['peserta'],
            'keterangan'  => $validated['keterangan'],
        ];

        if ($request->hasFile('bukti_bayar')) {
            if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }

            $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        $pembayaran->update($data);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}
