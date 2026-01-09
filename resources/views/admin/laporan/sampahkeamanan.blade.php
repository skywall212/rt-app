@extends('layouts.app')

@section('content')
<style>
    .laporan-title { font-weight: bold; letter-spacing: 1px; }
    .table-laporan { font-size: 9px; }
    .table-laporan thead th {
        background: linear-gradient(90deg, #007bff, #00c6ff);
        color: white;
        text-transform: uppercase;
        font-size: 9px;
    }
    .table-laporan tbody tr:nth-child(even) { background-color: #f9f9f9; }
    .tanggal-kecil { font-size: 8px; color: #6c757d; display: block; }
    .nominal-bold { font-weight: 600; }
    .summary { margin-top: 20px; }
</style>

<div class="container-fluid">

    {{-- FILTER TAHUN --}}
    <form method="GET" action="{{ route('admin.laporan.sampahkeamanan') }}" class="row g-2 mb-3">
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
            <a href="{{ route('admin.laporan.sampahkeamanan_pdf', ['tahun' => $tahun]) }}"
               class="btn btn-danger btn-sm" target="_blank">
                ⬇️ Export PDF
            </a>
        </div>
    </form>

    {{-- JUDUL --}}
    <h4 class="text-center laporan-title mb-1">
        📑 PEMASUKAN SAMPAH KEAMANAN WARGA RT.04
    </h4>
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
                            <th>JML BLN</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($laporan as $item)
                            @php $jumlahBulan = 0; @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $item['nama'] }}</td>
                                <td class="text-start">{{ $item['alamat'] }}</td>

                                @for($i = 1; $i <= 12; $i++)
                                    <td>
                                        @if($item['bulan'][$i]['jumlah'] > 0)
                                            <div class="nominal-bold">
                                                Rp{{ number_format($item['bulan'][$i]['jumlah'], 0, ',', '.') }}
                                            </div>
                                            @if($item['bulan'][$i]['tanggal'])
                                                <span class="tanggal-kecil">
                                                    {{ $item['bulan'][$i]['tanggal'] }}
                                                </span>
                                            @endif
                                            @php $jumlahBulan++; @endphp
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endfor

                                <td>
                                    <span class="badge bg-primary">{{ $jumlahBulan }}</span>
                                </td>

                                <td>
                                    <strong>
                                        Rp{{ number_format($item['total'], 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17" class="text-muted text-center">
                                    Belum ada data pembayaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- RINGKASAN --}}
            <div class="summary">
                <table>
                    <tr>
                        <td><strong>Total Warga Membayar</strong></td>
                        <td>:</td>
                        <td>{{ $totalPeserta }} orang</td>
                    </tr>
                    <tr>
                        <td><strong>Total Pembayaran</strong></td>
                        <td>:</td>
                        <td>Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
