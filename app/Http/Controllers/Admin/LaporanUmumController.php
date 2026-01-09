<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanUmumController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $laporanSK       = $this->getLaporanByJenis('Sampah Keamanan', $tahun);
        $laporanDansos   = $this->getLaporanByJenis('Dana Sosial', $tahun);
        $laporanPulasara = $this->getLaporanByJenis('Pulasara', $tahun);

        // total peserta pulasara (akumulasi)
        $totalPesertaPulasara = collect($laporanPulasara)->sum('peserta');

        $laporanPengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('admin.laporan.umum', compact(
            'tahun',
            'laporanSK',
            'laporanDansos',
            'laporanPulasara',
            'totalPesertaPulasara',
            'laporanPengeluaran'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $laporanSK       = $this->getLaporanByJenis('Sampah Keamanan', $tahun);
        $laporanDansos   = $this->getLaporanByJenis('Dana Sosial', $tahun);
        $laporanPulasara = $this->getLaporanByJenis('Pulasara', $tahun);

        $totalPesertaPulasara = collect($laporanPulasara)->sum('peserta');

        $laporanPengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.laporan.umum_pdf', compact(
            'tahun',
            'laporanSK',
            'laporanDansos',
            'laporanPulasara',
            'totalPesertaPulasara',
            'laporanPengeluaran'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream("laporan-umum-{$tahun}.pdf");
    }

    /**
     * 🔹 Ambil laporan per jenis pembayaran
     * 🔹 Menggunakan field bulan_bayar
     */
    private function getLaporanByJenis(string $jenis, int $tahun): array
    {
        // mapping bulan_bayar → angka bulan
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
            ->where('jenis', $jenis)
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
                    'nama'    => $pembayaran->warga->nama ?? '-',
                    'alamat'  => $pembayaran->warga->alamat ?? '-',
                    'peserta' => $pembayaran->peserta ?? 0,
                    'bulan'   => array_fill(1, 12, [
                        'jumlah'  => 0,
                        'tanggal' => null
                    ]),
                    'total'   => 0,
                ];
            }

            // isi data per bulan
            $laporan[$wargaId]['bulan'][$bulanKe] = [
                'jumlah'  => $pembayaran->jumlah,
                'tanggal' => Carbon::parse($pembayaran->tanggal)->format('d/m/Y'),
            ];

            // akumulasi total
            $laporan[$wargaId]['total'] += $pembayaran->jumlah;
        }

        return collect($laporan)
            ->sortBy('nama')
            ->values()
            ->toArray();
    }
}
