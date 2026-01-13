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

        $totalPeserta = count($laporan);
        $grandTotal   = array_sum(array_column($laporan, 'total'));

        return view(
            'admin.laporan.sampahkeamanan',
            compact('laporan', 'tahun', 'totalPeserta', 'grandTotal')
        );
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $laporan = $this->getLaporanSampahKeamanan($tahun);

        $totalPeserta = count($laporan);
        $grandTotal   = array_sum(array_column($laporan, 'total'));

        $pdf = Pdf::loadView(
            'admin.laporan.sampahkeamanan_pdf',
            compact('laporan', 'tahun', 'totalPeserta', 'grandTotal')
        )->setPaper('a4', 'landscape');

        return $pdf->stream("laporan_sampah_keamanan_{$tahun}.pdf");
    }

    /**
     * 🔹 Ambil laporan Sampah Keamanan berdasarkan BULAN_BAYAR
     */
    private function getLaporanSampahKeamanan($tahun)
    {
        // Mapping nama bulan → angka bulan
        $mapBulan = [
            'Januari'   => 1,
            'Februari'  => 2,
            'Maret'     => 3,
            'April'     => 4,
            'Mei'       => 5,
            'Juni'      => 6,
            'Juli'      => 7,
            'Agustus'   => 8,
            'September' => 9,
            'Oktober'   => 10,
            'November'  => 11,
            'Desember'  => 12,
        ];

        $pembayarans = Pembayaran::with('warga')
            ->where('jenis', 'Sampah Keamanan')
            ->whereYear('tanggal', $tahun)
            ->whereNotNull('bulan_bayar')
            ->get();

        $laporan = [];

        foreach ($pembayarans as $pembayaran) {

            // skip jika bulan tidak valid
            if (!isset($mapBulan[$pembayaran->bulan_bayar])) {
                continue;
            }

            $bulanKe = $mapBulan[$pembayaran->bulan_bayar];
            $wargaId = $pembayaran->warga_id;

            if (!isset($laporan[$wargaId])) {
                $laporan[$wargaId] = [
                    'nama'   => $pembayaran->warga->nama ?? '-',
                    'alamat' => $pembayaran->warga->alamat ?? '-',
                    'bulan'  => array_fill(1, 12, [
                        'jumlah'  => 0,
                        'tanggal' => null
                    ]),
                    'total'  => 0,
                ];
            }

            // isi data per bulan
            $laporan[$wargaId]['bulan'][$bulanKe] = [
                'jumlah'  => $pembayaran->jumlah,
                'tanggal' => Carbon::parse($pembayaran->tanggal)->format('d/m/Y'),
            ];

            // total per warga
            $laporan[$wargaId]['total'] += $pembayaran->jumlah;
        }

        // Urutkan berdasarkan blok dan nomor rumah
        return collect($laporan)
        ->sortBy(function ($item) {
            // contoh alamat: G8/12
            $alamat = $item['alamat'];

            if (preg_match('/([A-Z]+)(\d+)\/(\d+)/i', $alamat, $m)) {
                // $m[1] = huruf (G)
                // $m[2] = blok (8, 9, dst)
                // $m[3] = nomor rumah (1..20)

                return sprintf(
                    '%s-%03d-%03d',
                    strtoupper($m[1]),
                    (int) $m[2],
                    (int) $m[3]
                );
            }

            // fallback jika format tidak sesuai
            return $alamat;
        })
        ->values()
        ->toArray();


    }
}
