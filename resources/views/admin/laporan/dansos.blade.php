{{-- resources/views/admin/laporan/dansos.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .laporan-title { font-weight: bold; letter-spacing: 1px; }
    .table-laporan { font-size: 9px; }
    .table-laporan thead th {
        background: linear-gradient(90deg, #007bff, #00c6ff);
        color: white; text-transform: uppercase; font-size: 9px;
    }
    .table-laporan tbody tr:nth-child(even) { background-color: #f9f9f9; }
    .tanggal-kecil { font-size: 8px; color: #6c757d; display:block; }
    .nominal-bold { font-weight: 600; }
</style>

<div class="container-fluid">
    <form method="GET" action="{{ route('admin.laporan.dansos') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                @for ($t = date('Y'); $t >= date('Y') - 5; $t--)
                    <option value="{{ $t }}" {{ (int)$tahun === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endfor
            </select>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.laporan.dansos_pdf', ['tahun' => $tahun]) }}" class="btn btn-danger btn-sm" target="_blank">⬇️ Export PDF</a>
        </div>
    </form>

    <h4 class="text-center laporan-title mb-2">📑 PEMASUKAN DANA SOSIAL WARGA RT.04</h4>
    <h6 class="text-center mb-4">TAHUN {{ $tahun }}</h6>

    <div class="card shadow-sm">
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-laporan text-center align-middle">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA</th>
                            <th>ALAMAT</th>
                            <th>PESERTA</th>
                            <th>JAN</th><th>FEB</th><th>MAR</th><th>APR</th>
                            <th>MEI</th><th>JUN</th><th>JUL</th><th>AGUST</th>
                            <th>SEPT</th><th>OKT</th><th>NOV</th><th>DES</th>
                            <th>BULAN</th>
                            <th>JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $item['nama'] }}</td>
                                <td class="text-start">{{ $item['alamat'] }}</td>
                                <td class="text-center">{{ $item['peserta'] }}</td>

                                {{-- tampilkan per bulan --}}
                                @for($i = 1; $i <= 12; $i++)
                                    <td>
                                        @if(!empty($item['bulan'][$i]['jumlah']) && $item['bulan'][$i]['jumlah'] > 0)
                                            <div class="nominal-bold">
                                                Rp{{ number_format($item['bulan'][$i]['jumlah'], 0, ',', '.') }}
                                            </div>
                                            @if(!empty($item['bulan'][$i]['tanggal']))
                                                <span class="tanggal-kecil">
                                                    {{ \Carbon\Carbon::parse($item['bulan'][$i]['tanggal'])->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endfor

                                <td><span class="badge bg-primary">{{ $item['jumlah_bulan'] ?? 0 }}</span></td>
                                <td><strong>Rp{{ number_format($item['total'], 0, ',', '.') }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="18" class="text-muted">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                <table class="table table-borderless table-sm" style="font-size: 10px; width: auto;">
                    <tr>
                        <td style="font-weight:bold;">Total Pembayaran Dana Sosial</td>
                        <td style="text-align:center; width:10px;">:</td>
                        <td>Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
