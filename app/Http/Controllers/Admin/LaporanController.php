<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function pembayaran(Request $request)
    {
        $query = Pembayaran::query();
        
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }
        if ($request->filled('jenis')) {
        $query->where('jenis', $request->jenis);
        }
        $pembayaran = $query->orderBy('tanggal', 'desc')->get();
        $jenisList = Pembayaran::select('jenis')->distinct()->pluck('jenis');

        return view('admin.laporan.pembayaran', compact('pembayaran', 'jenisList'));

    }


    public function pengeluaran(Request $request)
    {
        $query = Pengeluaran::query();

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $pengeluaran = $query->orderBy('tanggal', 'desc')->get();

        return view('admin.laporan.pengeluaran', compact('pengeluaran'));
    }
    


    public function pembayaranExportPDF(Request $request)
    {
        $query = Pembayaran::query();

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $pembayaran = $query->get();
    $pdf = PDF::loadView('admin.laporan.pembayaran_pdf', compact('pembayaran'));
    return $pdf->stream('laporan_pembayaran.pdf');

    }
    public function pengeluaranExportPDF(Request $request)
    {
        $query = Pengeluaran::query();

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $pengeluaran = $query->get();
        $pdf = PDF::loadView('admin.laporan.pengeluaran_pdf', compact('pengeluaran'));
        return $pdf->stream('laporan_pengeluaran.pdf');
    }
}