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
    public function index()
    {
        $currentYear = Carbon::now()->year;

        // Statistik total
        $totalSampahKeamanan = Pembayaran::where('jenis', 'Sampah Keamanan')->sum('jumlah');
        $totalDanaSosial     = Pembayaran::where('jenis', 'Dana Sosial')->sum('jumlah');
        $totalPulasara       = Pembayaran::where('jenis', 'Pulasara')->sum('jumlah');
        $totalSumbangan      = Pembayaran::where('jenis', 'Sumbangan')->sum('jumlah');
        $totalPengeluaran    = Pengeluaran::sum('jumlah');
        $totalUser           = User::count();

        // Data chart bulanan
        $labels              = [];
        $dataSampahKeamanan  = [];
        $dataDanaSosial      = [];
        $dataPulasara        = [];
        $dataSumbangan       = [];
        $dataPengeluaran     = [];

        // Data pengeluaran per jenis
        $dataDansos       = [];
        $dataPulasaraOut  = [];
        $dataIuranSKRT04  = [];
        $dataSumbanganOut = [];
        $dataSumbanganRW05= [];
        $dataKasRT        = [];
        $dataBankAdm      = [];
        $dataPosyandu     = [];
        $dataPosbindu     = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan = Carbon::create($currentYear, $i, 1)->locale('id')->format('M');
            $labels[] = $bulan;

            // Pembayaran
            $dataSampahKeamanan[] = Pembayaran::where('jenis', 'Sampah Keamanan')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataDanaSosial[] = Pembayaran::where('jenis', 'Dana Sosial')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataPulasara[] = Pembayaran::where('jenis', 'Pulasara')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataSumbangan[] = Pembayaran::where('jenis', 'Sumbangan')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            // Pengeluaran total
            $dataPengeluaran[] = Pengeluaran::whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            // Pengeluaran per jenis
            $dataDansos[] = Pengeluaran::where('jenis', 'Dansos')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataPulasaraOut[] = Pengeluaran::where('jenis', 'Pulasara')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataIuranSKRT04[] = Pengeluaran::where('jenis', 'Iuran SK RT04')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataSumbanganOut[] = Pengeluaran::where('jenis', 'Sumbangan')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataSumbanganRW05[] = Pengeluaran::where('jenis', 'Sumbangan RW05')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataKasRT[] = Pengeluaran::where('jenis', 'Kas RT')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataBankAdm[] = Pengeluaran::where('jenis', 'Bank Adm')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataPosyandu[] = Pengeluaran::where('jenis', 'Posyandu')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;

            $dataPosbindu[] = Pengeluaran::where('jenis', 'Posbindu')
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $i)
                ->sum('jumlah') ?: 0;
        }

        return view('admin.dashboard', compact(
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
            'dataPengeluaran',
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
