<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    protected $mapBulan = [
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

    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $laporanSK        = $this->getLaporanByJenis('Sampah Keamanan', $tahun);
        $laporanDansos    = $this->getLaporanByJenis('Dana Sosial', $tahun);
        $laporanPulasara  = $this->getLaporanByJenis('Pulasara', $tahun);

        $totalPesertaPulasara = collect($laporanPulasara)->sum('peserta');

        // Pengeluaran tetap pakai tanggal (karena tidak punya bulan_bayar)
        $laporanPengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        // ===== Summary Total (Pembayaran berdasarkan tahun dari tanggal) =====
        $totalSampahKeamanan = Pembayaran::where('jenis', 'Sampah Keamanan')
            ->whereYear('tanggal', $tahun)->sum('jumlah');

        $totalDanaSosial = Pembayaran::where('jenis', 'Dana Sosial')
            ->whereYear('tanggal', $tahun)->sum('jumlah');

        $totalPulasara = Pembayaran::where('jenis', 'Pulasara')
            ->whereYear('tanggal', $tahun)->sum('jumlah');

        $totalSumbangan = Pembayaran::where('jenis', 'Sumbangan')
            ->whereYear('tanggal', $tahun)->sum('jumlah');

        $totalPengeluaran = $laporanPengeluaran->sum('jumlah');

        return view('users.laporan.index', compact(
            'tahun',
            'laporanSK',
            'laporanDansos',
            'laporanPulasara',
            'totalPesertaPulasara',
            'laporanPengeluaran',
            'totalSampahKeamanan',
            'totalDanaSosial',
            'totalPulasara',
            'totalSumbangan',
            'totalPengeluaran'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $laporanSK        = $this->getLaporanByJenis('Sampah Keamanan', $tahun);
        $laporanDansos    = $this->getLaporanByJenis('Dana Sosial', $tahun);
        $laporanPulasara  = $this->getLaporanByJenis('Pulasara', $tahun);
        $totalPesertaPulasara = collect($laporanPulasara)->sum('peserta');

        $laporanPengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalSampahKeamanan = Pembayaran::where('jenis', 'Sampah Keamanan')
            ->whereYear('tanggal', $tahun)->sum('jumlah');

        $totalDanaSosial = Pembayaran::where('jenis', 'Dana Sosial')
            ->whereYear('tanggal', $tahun)->sum('jumlah');

        $totalPulasara = Pembayaran::where('jenis', 'Pulasara')
            ->whereYear('tanggal', $tahun)->sum('jumlah');

        $totalSumbangan = Pembayaran::where('jenis', 'Sumbangan')
            ->whereYear('tanggal', $tahun)->sum('jumlah');

        $totalPengeluaran = $laporanPengeluaran->sum('jumlah');

        $pdf = Pdf::loadView('users.laporan.pdf', compact(
            'tahun',
            'laporanSK',
            'laporanDansos',
            'laporanPulasara',
            'totalPesertaPulasara',
            'laporanPengeluaran',
            'totalSampahKeamanan',
            'totalDanaSosial',
            'totalPulasara',
            'totalSumbangan',
            'totalPengeluaran'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream("laporan-users-{$tahun}.pdf");
    }

    private function getLaporanByJenis($jenis, $tahun)
    {
        $pembayarans = Pembayaran::where('jenis', $jenis)
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
            $tanggal = $pembayaran->tanggal
                ? Carbon::parse($pembayaran->tanggal)->format('d/m/Y')
                : null;

            $bulanText = $pembayaran->bulan_bayar;
            $bulanKe   = $this->mapBulan[$bulanText] ?? null;

            if (!$bulanKe) {
                continue; // skip jika bulan_bayar tidak valid
            }

            if (!isset($laporan[$wargaId])) {
                $laporan[$wargaId] = [
                    'nama'    => $nama,
                    'alamat'  => $alamat,
                    'peserta' => $peserta,
                    'bulan'   => array_fill(1, 12, ['jumlah' => 0, 'tanggal' => null]),
                    'total'   => 0,
                ];
            }

            $laporan[$wargaId]['bulan'][$bulanKe] = [
                'jumlah'  => $jumlah,
                'tanggal' => $tanggal,
            ];

            $laporan[$wargaId]['total'] += $jumlah;
        }

        //return collect($laporan)->sortBy('nama')->values()->toArray();
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
