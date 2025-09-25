<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanPulasaraController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $laporan = $this->getLaporanPulasara($tahun);

        $totalPeserta = count($laporan);
        $grandTotal = array_sum(array_column($laporan, 'total'));

        return view('admin.laporan.pulasara', compact('laporan', 'tahun', 'totalPeserta', 'grandTotal'));
    }

    public function exportPdf(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $laporan = $this->getLaporanPulasara($tahun);

        $pdf = Pdf::loadView('admin.laporan.pulasara_pdf', compact('laporan', 'tahun'))
                ->setPaper('a4', 'landscape');

        return $pdf->stream("laporan_pulasara_{$tahun}.pdf");
    }

    /**
     * 🔹 Ambil dan mapping data laporan pulasara per tahun
     *    -> setiap $item['bulan'][1..12] sekarang menjadi array:
     *       ['jumlah' => 0|5000, 'tanggal' => 'YYYY-MM-DD'|null]
     */
    private function getLaporanPulasara($tahun)
    {
        $pembayarans = Pembayaran::where('jenis', 'Pulasara')
            ->whereYear('tanggal', $tahun)
            ->with('warga') // pastikan relasi 'warga' ada di model Pembayaran
            ->get();

        $laporan = [];

        foreach ($pembayarans as $pembayaran) {
            $nama    = $pembayaran->warga->nama ?? '-';
            $alamat  = $pembayaran->warga->alamat ?? '-';
            $peserta = $pembayaran->peserta ?? '-';

            // hitung berapa bulan terbayar (1 bulan = 5000)
            $bulanTerbayar = intdiv((int)$pembayaran->jumlah, 5000);
            if ($bulanTerbayar > 12) $bulanTerbayar = 12;

            // inisialisasi 12 bulan: setiap bulan => ['jumlah' => 0, 'tanggal' => null]
            $bulanArr = [];
            for ($m = 1; $m <= 12; $m++) {
                $bulanArr[$m] = [
                    'jumlah' => 0,
                    'tanggal' => null,
                ];
            }

            // isi bulan 1..$bulanTerbayar (menjaga perilaku Anda sebelumnya)
            // setiap bulan diisi nominal 5000 dan tanggal diisi dengan tanggal pembayaran
            $tglPembayaran = $pembayaran->tanggal ? Carbon::parse($pembayaran->tanggal)->format('Y-m-d') : null;
            for ($i = 1; $i <= $bulanTerbayar; $i++) {
                $bulanArr[$i]['jumlah'] = 5000;
                $bulanArr[$i]['tanggal'] = $tglPembayaran;
            }

            // hitung ringkasan
            $jumlah_bulan = 0;
            $total_nominal = 0;
            for ($m = 1; $m <= 12; $m++) {
                if ($bulanArr[$m]['jumlah'] > 0) {
                    $jumlah_bulan++;
                    $total_nominal += $bulanArr[$m]['jumlah'];
                }
            }

            $laporan[] = [
                'nama' => $nama,
                'alamat' => $alamat,
                'peserta' => $peserta,
                'bulan' => $bulanArr,
                'jumlah_bulan' => $jumlah_bulan,
                //'total' => $total_nominal,
                'total' => $pembayaran->jumlah,
            ];
        }

        // urutkan berdasarkan alamat (sama seperti sebelumnya)
        return collect($laporan)->sortBy('alamat')->values()->toArray();
    }
}
