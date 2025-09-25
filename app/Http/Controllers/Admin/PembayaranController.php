<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\MasterWarga;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    // Tampilkan daftar pembayaran
    public function index(Request $request)
    {
        $query = Pembayaran::with('warga')->orderBy('tanggal', 'desc');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('warga', function ($q) use ($search) {
                $q->where('nama', 'ILIKE', "%{$search}%");
            })
            ->orWhere('jenis', 'ILIKE', "%{$search}%");
        }

        $pembayarans = $query->paginate(10);

        // agar search ikut dibawa saat pindah halaman
        $pembayarans->appends($request->all());

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    // Form tambah pembayaran
    public function create()
    {
        $warga = MasterWarga::all(); // ambil semua warga
        return view('admin.pembayaran.create', compact('warga'));
    }

    // Simpan pembayaran baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'warga_id'   => 'required|exists:master_warga,id',
            'tanggal'    => 'required|date',
            'jenis'      => 'required|in:Sampah Keamanan,Dana Sosial,Pulasara,Sumbangan',
            'peserta'    => 'nullable|integer|min:1',
            'jumlah_pulasara' => 'nullable|numeric|min:0',
            'jumlah_general'  => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_bayar'=> 'nullable|image|max:2048',
        ]);

        // 🔎 Cek apakah warga sudah pernah bayar Pulasara
        if ($validated['jenis'] === 'Pulasara') {
            $sudahBayar = Pembayaran::where('warga_id', $validated['warga_id'])
                ->where('jenis', 'Pulasara')
                ->exists();

            if ($sudahBayar) {
                return redirect()->back()
                    ->withInput()
                    ->with('warning', '❗❗ Pulasara sudah dibayarkan.❗❗');
            }
        }

        // 🔎 Cek apakah warga sudah pernah bayar Sampah Keamanan di bulan yang sama
        if ($validated['jenis'] === 'Sampah Keamanan') {
            $bulan = \Carbon\Carbon::parse($validated['tanggal'])->month;
            $tahun = \Carbon\Carbon::parse($validated['tanggal'])->year;

            $sudahBayarSK = Pembayaran::where('warga_id', $validated['warga_id'])
                ->where('jenis', 'Sampah Keamanan')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->exists();

            if ($sudahBayarSK) {
                return redirect()->back()
                    ->withInput()
                    ->with('warning', '‼️ Sampah Keamanan sudah dibayarkan untuk bulan ini.‼️');
            }
        }


        // 🔎 Cek apakah warga sudah pernah bayar Dana Sosial di bulan yang sama
        if ($validated['jenis'] === 'Dana Sosial') {
            $bulan = \Carbon\Carbon::parse($validated['tanggal'])->month;
            $tahun = \Carbon\Carbon::parse($validated['tanggal'])->year;

            $sudahBayarDS = Pembayaran::where('warga_id', $validated['warga_id'])
                ->where('jenis', 'Dana Sosial')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->exists();

            if ($sudahBayarDS) {
                return redirect()->back()
                    ->withInput()
                    ->with('warning', '‼️ Dana Sosial sudah dibayarkan untuk bulan ini.‼️');
            }
        }
        
        $data = [
            'warga_id'   => $validated['warga_id'],
            'tanggal'    => $validated['tanggal'],
            'jenis'      => $validated['jenis'],
            'peserta'    => $validated['jenis'] === 'Pulasara' ? $validated['peserta'] : null,
            'jumlah'     => $validated['jenis'] === 'Pulasara'
                            ? $validated['jumlah_pulasara']
                            : $validated['jumlah_general'],
            'keterangan' => $validated['keterangan'] ?? null,
        ];

        // upload bukti bayar
        if ($request->hasFile('bukti_bayar')) {
            $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        Pembayaran::create($data);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran berhasil disimpan.');
    }


    // Tampilkan form edit
    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $warga = MasterWarga::all();
        return view('admin.pembayaran.edit', compact('pembayaran', 'warga'));
    }

    // Update data pembayaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'warga_id'   => 'required|exists:master_warga,id',
            'tanggal'    => 'required|date',
            'jenis'      => 'required|string|max:255',
            'jumlah'     => 'required|numeric',
            'peserta'    => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
            'bukti_bayar'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $data = $request->only([
            'warga_id', 'tanggal', 'jenis', 'jumlah', 'peserta', 'keterangan'
        ]);

        // Upload bukti bayar baru jika ada
        if ($request->hasFile('bukti_bayar')) {
            // Hapus file lama kalau ada
            if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }

            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $data['bukti_bayar'] = $path;
        }

        $pembayaran->update($data);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    // Hapus pembayaran
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        // Hapus bukti bayar dari storage
        if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}
