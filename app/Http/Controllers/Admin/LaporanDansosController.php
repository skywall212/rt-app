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
        $tahun   = $request->input('tahun', date('Y'));
        $laporan = $this->getLaporanDansos($tahun);

        $totalPeserta = count($laporan);
        $grandTotal   = array_sum(array_column($laporan, 'total'));

        return view('admin.laporan.dansos', compact(
            'laporan',
            'tahun',
            'totalPeserta',
            'grandTotal'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tahun   = $request->input('tahun', date('Y'));
        $laporan = $this->getLaporanDansos($tahun);

        $totalPeserta = count($laporan);
        $grandTotal   = array_sum(array_column($laporan, 'total'));

        $pdf = Pdf::loadView('admin.laporan.dansos_pdf', compact(
            'laporan',
            'tahun',
            'totalPeserta',
            'grandTotal'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream("laporan_dansos_{$tahun}.pdf");
    }

    /**
     * ===============================
     * LAPORAN DANSOS (PAKAI bulan_bayar)
     * ===============================
     */
    private function getLaporanDansos($tahun)
    {
        $pembayarans = Pembayaran::where('jenis', 'Dana Sosial')
            ->whereYear('tanggal', $tahun)
            ->with('warga')
            ->get();

        // Mapping nama bulan → index kolom
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

        $laporan = [];

        foreach ($pembayarans as $pembayaran) {

            // 🔒 Skip jika bulan_bayar kosong
            if (empty($pembayaran->bulan_bayar)) {
                continue;
            }

            $bulanKe = $mapBulan[$pembayaran->bulan_bayar] ?? null;
            if (!$bulanKe) {
                continue;
            }

            $wargaId = $pembayaran->warga_id;

            if (!isset($laporan[$wargaId])) {
                $laporan[$wargaId] = [
                    'nama'   => $pembayaran->warga->nama ?? '-',
                    'alamat' => $pembayaran->warga->alamat ?? '-',
                    'bulan'  => array_fill(1, 12, [
                        'jumlah'  => 0,
                        'tanggal' => null,
                    ]),
                    'jumlah_bulan' => 0,
                    'total' => 0,
                ];
            }

            // isi bulan berdasarkan bulan_bayar
            $laporan[$wargaId]['bulan'][$bulanKe] = [
                'jumlah'  => $pembayaran->jumlah,
                'tanggal' => Carbon::parse($pembayaran->tanggal)->format('d/m/Y'),
            ];

            $laporan[$wargaId]['total'] += $pembayaran->jumlah;
        }

        // hitung jumlah bulan terisi
        foreach ($laporan as &$row) {
            $row['jumlah_bulan'] = collect($row['bulan'])
                ->filter(fn ($b) => $b['jumlah'] > 0)
                ->count();
        }
        // urutkan berdasarkan blok dan nomor rumah
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
