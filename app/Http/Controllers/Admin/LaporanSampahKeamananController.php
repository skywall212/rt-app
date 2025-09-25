<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanSampahKeamananController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $laporan = $this->getLaporanSampahKeamanan($tahun);

        // 🔹 Total peserta = jumlah warga unik yang membayar
        $totalPeserta = count($laporan);

        // 🔹 Total pembayaran = jumlah total dari semua warga
        $grandTotal = array_sum(array_column($laporan, 'total'));

        return view('admin.laporan.sampahkeamanan', compact('laporan', 'tahun', 'totalPeserta', 'grandTotal'));
    }



    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $laporan = $this->getLaporanSampahKeamanan($tahun);

        $totalPeserta = count($laporan);
        $grandTotal = array_sum(array_column($laporan, 'total'));

        $pdf = Pdf::loadView('admin.laporan.sampahkeamanan_pdf', compact('laporan', 'tahun', 'totalPeserta', 'grandTotal'))
                ->setPaper('a4', 'landscape');

        return $pdf->stream("laporan_sampah_keamanan_{$tahun}.pdf");
    }

    /**
     * 🔹 Ambil dan mapping data laporan Sampah Keamanan per tahun
     */
    private function getLaporanSampahKeamanan($tahun)
    {
        $pembayarans = Pembayaran::where('jenis', 'Sampah Keamanan')
            ->whereYear('tanggal', $tahun)
            ->get();

        $laporan = [];

        foreach ($pembayarans as $pembayaran) {
            $wargaId = $pembayaran->warga_id;
            $nama    = $pembayaran->warga->nama ?? '-';
            $alamat  = $pembayaran->warga->alamat ?? '-';
            $jumlah  = $pembayaran->jumlah;
            $tanggal = Carbon::parse($pembayaran->tanggal)->format('d/m/Y');
            $bulanKe = Carbon::parse($pembayaran->tanggal)->month;

            // jika warga belum ada di laporan, inisialisasi
            if (!isset($laporan[$wargaId])) {
                $laporan[$wargaId] = [
                    'nama'   => $nama,
                    'alamat' => $alamat,
                    'bulan'  => array_fill(1, 12, ['jumlah' => 0, 'tanggal' => null]),
                    'total'  => 0,
                ];
            }

            // Tambahkan jumlah ke bulan yang sesuai
            $laporan[$wargaId]['bulan'][$bulanKe] = [
                'jumlah'   => $jumlah,
                'tanggal'  => $tanggal,
                'total' => $pembayaran->jumlah,
            ];

            // Tambahkan ke total
            $laporan[$wargaId]['total'] += $jumlah;
        }

        // urutkan berdasarkan nama
        return collect($laporan)->sortBy('nama')->values()->toArray();
    }
}
