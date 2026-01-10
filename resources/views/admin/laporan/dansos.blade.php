{{-- resources/views/admin/laporan/dansos.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    .laporan-title {
        font-weight: bold;
        letter-spacing: 1px;
    }

    .table-laporan {
        font-size: 11px;
        border-collapse: collapse !important;
        width: 100%;
    }

    .table-laporan th,
    .table-laporan td {
        border: 1px solid #000 !important;
        padding: 4px;
        vertical-align: middle;
        text-align: center;
    }

    .table-laporan thead th {
        background: #0d6efd;
        color: #fff;
        font-size: 11px;
        text-transform: uppercase;
    }

    .table-laporan tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .tanggal-kecil {
        font-size: 9px;
        color: #6c757d;
        display: block;
    }

    .nominal-bold {
        font-weight: 600;
        white-space: nowrap;
    }
</style>

<div class="container-fluid">

    {{-- FILTER TAHUN --}}
    <form method="GET" action="{{ route('admin.laporan.dansos') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                @for ($t = date('Y'); $t >= date('Y') - 5; $t--)
                    <option value="{{ $t }}" {{ (int)$tahun === $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.laporan.dansos_pdf', ['tahun' => $tahun]) }}"
               class="btn btn-danger btn-sm" target="_blank">
                ⬇️ Export PDF
            </a>
        </div>
    </form>

    {{-- JUDUL --}}
    <h4 class="text-center laporan-title mb-1">
        📄 PEMASUKAN DANA SOSIAL WARGA RT.04
    </h4>
    <h6 class="text-center mb-3">
        TAHUN {{ $tahun }}
    </h6>

    {{-- TABEL --}}
    <div class="card shadow-sm">
        <div class="card-body p-2">

            <table class="table table-laporan">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th style="width:180px;">NAMA</th>
                        <th style="width:120px;">ALAMAT</th>
                        <th>JAN</th>
                        <th>FEB</th>
                        <th>MAR</th>
                        <th>APR</th>
                        <th>MEI</th>
                        <th>JUN</th>
                        <th>JUL</th>
                        <th>AGUST</th>
                        <th>SEPT</th>
                        <th>OKT</th>
                        <th>NOV</th>
                        <th>DES</th>
                        <th>BULAN</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $item['nama'] }}</td>
                            <td class="text-center">{{ $item['alamat'] }}</td>


                            {{-- JAN–DES --}}
                            @for($i = 1; $i <= 12; $i++)
                                <td>
                                    @if($item['bulan'][$i]['jumlah'] > 0)
                                        <span class="nominal-bold">
                                            Rp{{ number_format($item['bulan'][$i]['jumlah'], 0, ',', '.') }}
                                        </span>
                                        <span class="tanggal-kecil">
                                            {{ $item['bulan'][$i]['tanggal'] }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endfor

                            <td>
                                <span class="badge bg-primary">
                                    {{ $item['jumlah_bulan'] }}
                                </span>
                            </td>

                            <td class="text-end">
                                <strong>
                                    Rp{{ number_format($item['total'], 0, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="17" class="text-muted">
                                Belum ada data pembayaran Dana Sosial
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- TOTAL --}}
            <div class="mt-2">
                <table class="table table-borderless table-sm" style="width:auto;">
                    <tr>
                        <td style="font-weight:bold;">Total Pembayaran Dana Sosial</td>
                        <td style="width:10px;">:</td>
                        <td>
                            <strong>
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
