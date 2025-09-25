<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun dari request (default tahun sekarang)
        $tahun = $request->input('tahun', date('Y'));

        // ==== Pemasukan (dari tabel pembayarans) ====
        $pemasukan = DB::table('pembayarans')
            ->select(
                'jenis',
                DB::raw("EXTRACT(MONTH FROM tanggal) as bulan"),
                DB::raw("SUM(jumlah) as total")
            )
            ->whereRaw("EXTRACT(YEAR FROM tanggal) = ?", [$tahun])
            ->whereIn('jenis', ['Sampah Keamanan', 'Dana Sosial', 'Pulasara', 'Sumbangan'])
            ->groupBy('jenis', DB::raw("EXTRACT(MONTH FROM tanggal)"))
            ->orderBy('bulan')
            ->get();

        // ==== Pengeluaran (dari tabel pengeluarans) ====
        $pengeluaran = DB::table('pengeluarans')
            ->select(
                'jenis',
                DB::raw("EXTRACT(MONTH FROM tanggal) as bulan"),
                DB::raw("SUM(jumlah) as total")
            )
            ->whereRaw("EXTRACT(YEAR FROM tanggal) = ?", [$tahun])
            ->whereIn('jenis', [
                'Dansos',
                'Pulasara',
                'Iuran SK RT04',
                'Sumbangan',
                'Sumbangan RW05',
                'Kas RT',
                'Bank Adm',
                'Posyandu',
                'Posbindu'
            ])
            ->groupBy('jenis', DB::raw("EXTRACT(MONTH FROM tanggal)"))
            ->orderBy('bulan')
            ->get();

        // ==== Summary per kategori pemasukan ====
        $totalSampahKeamanan = DB::table('pembayarans')
            ->whereYear('tanggal', $tahun)
            ->where('jenis', 'Sampah Keamanan')
            ->sum('jumlah');

        $totalDanaSosial = DB::table('pembayarans')
            ->whereYear('tanggal', $tahun)
            ->where('jenis', 'Dana Sosial')
            ->sum('jumlah');

        $totalPulasara = DB::table('pembayarans')
            ->whereYear('tanggal', $tahun)
            ->where('jenis', 'Pulasara')
            ->sum('jumlah');

        $totalSumbangan = DB::table('pembayarans')
            ->whereYear('tanggal', $tahun)
            ->where('jenis', 'Sumbangan')
            ->sum('jumlah');

        // ==== Total Pengeluaran ====
        $totalPengeluaran = DB::table('pengeluarans')
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        // ==== Total User (misalnya dari tabel users) ====
        $totalUser = DB::table('users')->count();

        // ==== List Tahun dari pembayarans & pengeluarans ====
        $tahunList = DB::table('pembayarans')
            ->select(DB::raw("DISTINCT EXTRACT(YEAR FROM tanggal)::INT as tahun"))
            ->union(
                DB::table('pengeluarans')->select(DB::raw("DISTINCT EXTRACT(YEAR FROM tanggal)::INT as tahun"))
            )
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('users.dashboard', compact(
            'tahun',
            'tahunList',
            'pemasukan',
            'pengeluaran',
            'totalSampahKeamanan',
            'totalDanaSosial',
            'totalPulasara',
            'totalSumbangan',
            'totalPengeluaran',
            'totalUser'
        ));
    }
}
