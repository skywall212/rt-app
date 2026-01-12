<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', Carbon::now()->year);

        // Statistik total per tahun
        $totalSampahKeamanan = Pembayaran::where('jenis', 'Sampah Keamanan')
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $totalDanaSosial = Pembayaran::where('jenis', 'Dana Sosial')
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $totalPulasara = Pembayaran::where('jenis', 'Pulasara')
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $totalSumbangan = Pembayaran::where('jenis', 'Sumbangan')
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $totalPengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $totalUser = User::count();

        // Data chart
        $labels = [];
        $dataSampahKeamanan = [];
        $dataDanaSosial = [];
        $dataPulasara = [];
        $dataSumbangan = [];

        $dataDansos = [];
        $dataPulasaraOut = [];
        $dataIuranSKRT04 = [];
        $dataSumbanganOut = [];
        $dataSumbanganRW05 = [];
        $dataKasRT = [];
        $dataBankAdm = [];
        $dataPosyandu = [];
        $dataPosbindu = [];

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::create($tahun, $i, 1)->locale('id')->translatedFormat('M');

            // Pembayaran
            $dataSampahKeamanan[] = Pembayaran::where('jenis', 'Sampah Keamanan')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataDanaSosial[] = Pembayaran::where('jenis', 'Dana Sosial')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataPulasara[] = Pembayaran::where('jenis', 'Pulasara')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataSumbangan[] = Pembayaran::where('jenis', 'Sumbangan')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            // Pengeluaran per jenis
            $dataDansos[] = Pengeluaran::where('jenis', 'Dansos')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataPulasaraOut[] = Pengeluaran::where('jenis', 'Pulasara')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataIuranSKRT04[] = Pengeluaran::where('jenis', 'Iuran SK RT04')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataSumbanganOut[] = Pengeluaran::where('jenis', 'Sumbangan')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataSumbanganRW05[] = Pengeluaran::where('jenis', 'Sumbangan RW05')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataKasRT[] = Pengeluaran::where('jenis', 'Kas RT')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataBankAdm[] = Pengeluaran::where('jenis', 'Bank Adm')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataPosyandu[] = Pengeluaran::where('jenis', 'Posyandu')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataPosbindu[] = Pengeluaran::where('jenis', 'Posbindu')
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;
        }

        return view('admin.dashboard', compact(
            'tahun',
            'totalSampahKeamanan',
            'totalDanaSosial',
            'totalPulasara',
            'totalSumbangan',
            'totalPengeluaran',
            'totalUser',
            'labels',
            'dataSampahKeamanan',
            'dataDanaSosial',
            'dataPulasara',
            'dataSumbangan',
            'dataDansos',
            'dataPulasaraOut',
            'dataIuranSKRT04',
            'dataSumbanganOut',
            'dataSumbanganRW05',
            'dataKasRT',
            'dataBankAdm',
            'dataPosyandu',
            'dataPosbindu'
        ));
    }
}
