<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanDansosController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $laporan = $this->getLaporanDansos($tahun);

        $totalPeserta = count($laporan);
        $grandTotal = array_sum(array_column($laporan, 'total'));

        return view('admin.laporan.dansos', compact('laporan', 'tahun', 'totalPeserta', 'grandTotal'));
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $laporan = $this->getLaporanDansos($tahun);

        $totalPeserta = count($laporan);
        $grandTotal = array_sum(array_column($laporan, 'total'));

        $pdf = Pdf::loadView('admin.laporan.dansos_pdf', compact('laporan', 'tahun', 'totalPeserta', 'grandTotal'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream("laporan_dansos_{$tahun}.pdf");
    }

    /**
     * 🔹 Ambil dan mapping data laporan Dansos per tahun
     *    -> setiap $item['bulan'][1..12] menjadi array:
     *       ['jumlah' => 0|nominal, 'tanggal' => 'YYYY-MM-DD'|null]
     */
    private function getLaporanDansos($tahun)
    {
        $pembayarans = Pembayaran::where('jenis', 'Dana Sosial')
            ->whereYear('tanggal', $tahun)
            ->with('warga')
            ->get();

        $laporan = [];

        foreach ($pembayarans as $pembayaran) {
            $wargaId = $pembayaran->warga_id;
            $nama    = $pembayaran->warga->nama ?? '-';
            $alamat  = $pembayaran->warga->alamat ?? '-';
            $peserta = $pembayaran->peserta ?? 0;
            $jumlah  = $pembayaran->jumlah;
            $bulanKe = Carbon::parse($pembayaran->tanggal)->month;
            $tanggal = Carbon::parse($pembayaran->tanggal)->format('Y-m-d');

            // inisialisasi data warga jika belum ada
            if (!isset($laporan[$wargaId])) {
                $laporan[$wargaId] = [
                    'nama' => $nama,
                    'alamat' => $alamat,
                    'peserta' => $peserta,
                    'bulan' => array_fill(1, 12, ['jumlah' => 0, 'tanggal' => null]),
                    'jumlah_bulan' => 0,
                    'total' => 0,
                ];
            }

            // isi data bulan sesuai transaksi
            $laporan[$wargaId]['bulan'][$bulanKe] = [
                'jumlah' => $jumlah,
                'tanggal' => $tanggal,
            ];

            // update total
            $laporan[$wargaId]['total'] += $jumlah;
        }

        // hitung jumlah bulan terisi per warga
        foreach ($laporan as &$item) {
            $item['jumlah_bulan'] = collect($item['bulan'])->filter(fn($b) => $b['jumlah'] > 0)->count();
        }

        return collect($laporan)->sortBy('nama')->values()->toArray();
    }

}
