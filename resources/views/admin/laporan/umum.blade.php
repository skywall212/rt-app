@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            <i class="bi bi-journal-text"></i>
            Laporan Keuangan RT.04 Tahun {{ $tahun }}
        </h3>

        <div class="d-flex align-items-center">
            {{-- Pilih Tahun --}}
            <form action="{{ route('admin.laporan.umum') }}" method="GET" class="d-flex me-2">
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    @php
                        $currentYear = date('Y');
                        $startYear = $currentYear - 5;
                        $endYear = $currentYear + 1;
                    @endphp
                    @for ($y = $startYear; $y <= $endYear; $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </form>

            {{-- Tombol Export PDF --}}
            <a href="{{ route('admin.laporan.umum_pdf', ['tahun' => $tahun]) }}"
               class="btn btn-danger" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>
    </div>

    {{-- ===========================
     1. LAPORAN SAMPAH & KEAMANAN
    ============================ --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <b>Pemasukan Sampah & Keamanan</b>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        @for($i = 1; $i <= 12; $i++)
                            <th>{{ strtoupper(date('M', mktime(0,0,0,$i,1))) }}</th>
                        @endfor
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @php $no = 1; $totalSK = 0; @endphp

                    @forelse($laporanSK as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="text-start">{{ $row['nama'] }}</td>
                            <td class="text-start">{{ $row['alamat'] }}</td>

                            @for($i = 1; $i <= 12; $i++)
                                <td>
                                    @if($row['bulan'][$i]['jumlah'] > 0)
                                        <div class="fw-bold">
                                            Rp{{ number_format($row['bulan'][$i]['jumlah'],0,',','.') }}
                                        </div>
                                        @if($row['bulan'][$i]['tanggal'])
                                            <small class="text-muted">
                                                {{ $row['bulan'][$i]['tanggal'] }}
                                            </small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            @endfor

                            <td>
                                <b>Rp{{ number_format($row['total'],0,',','.') }}</b>
                            </td>
                        </tr>

                        @php $totalSK += $row['total']; @endphp
                    @empty
                        <tr>
                            <td colspan="16" class="text-muted text-center">
                                Belum ada data pembayaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="fw-bold mt-2">
                Total Pembayaran Sampah & Keamanan :
                Rp{{ number_format($totalSK,0,',','.') }}
            </div>
        </div>
    </div>

    {{-- ===========================
        2. LAPORAN DANA SOSIAL
    ============================ --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <b>Pemasukan Dana Sosial</b>
        </div>
        <div class="card-body">
            @if(!empty($laporanDansos))
                <div style="overflow-x: auto; white-space: nowrap;">
                    <table class="table table-bordered text-center align-middle mb-0" style="min-width: 100%; font-size: 0.85rem;">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 4%;">No</th>
                            <th style="width: 12%;">Nama</th>
                            <th style="width: 12%;">Alamat</th>
                            <th style="width: 6%;">Jan</th>
                            <th style="width: 6%;">Feb</th>
                            <th style="width: 6%;">Mar</th>
                            <th style="width: 6%;">Apr</th>
                            <th style="width: 6%;">Mei</th>
                            <th style="width: 6%;">Jun</th>
                            <th style="width: 6%;">Jul</th>
                            <th style="width: 6%;">Agust</th>
                            <th style="width: 6%;">Sept</th>
                            <th style="width: 6%;">Okt</th>
                            <th style="width: 6%;">Nov</th>
                            <th style="width: 6%;">Des</th>
                            <th style="width: 10%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; $totalDansos=0; @endphp
                        @foreach($laporanDansos as $row)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td class="text-start">{{ $row['nama'] }}</td>
                                <td class="text-start">{{ $row['alamat'] }}</td>
                               
                                {{-- Loop bulan 1 - 12 --}}
                                @for($i = 1; $i <= 12; $i++)
                                    <td style="padding: 0.5rem 0.25rem;">
                                        @if(!empty($row['bulan'][$i]['jumlah']) && $row['bulan'][$i]['jumlah'] > 0)
                                            <div class="fw-bold" style="font-size: 0.8rem;">
                                                Rp{{ number_format($row['bulan'][$i]['jumlah'], 0, ',', '.') }}
                                            </div>
                                            <small class="text-muted" style="font-size: 0.7rem; display: block;">
                                                @php
                                                    $tanggal = $row['bulan'][$i]['tanggal'];
                                                    $formatted = '';
                                                    
                                                    if (\Carbon\Carbon::hasFormat($tanggal, 'Y-m-d')) {
                                                        $formatted = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggal)->format('d/m/Y');
                                                    } else {
                                                        $formatted = $tanggal;
                                                    }
                                                @endphp
                                                {{ $formatted }}
                                            </small>

                                        @else
                                            -
                                        @endif
                                    </td>
                                @endfor

                                <td style="padding: 0.5rem;"><b>Rp{{ number_format($row['total'],0,',','.') }}</b></td>
                            </tr>
                            @php $totalDansos += $row['total']; @endphp
                        @endforeach
                    </tbody>
                    </table>
                </div>
                <div class="fw-bold mt-3">
                    Total Pembayaran Dana Sosial : Rp{{ number_format($totalDansos,0,',','.') }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Belum ada data pembayaran
                </div>
            @endif
        </div>
    </div>



    {{-- ===========================
    3. LAPORAN PULASARA
    ============================ --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-warning">
            <b>Pemasukan Pulasara</b>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-warning">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Jumlah Peserta</th>
                        <th>Tanggal Bayar</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; $totalPulasara = 0; @endphp
                    @foreach($laporanPulasara as $row)

                        @php
                            // Ambil tanggal terakhir dari bulan 1-12
                            $lastTanggal = '-';
                            for ($i = 12; $i >= 1; $i--) {
                                if (!empty($row['bulan'][$i]['tanggal'])) {
                                    $lastTanggal = $row['bulan'][$i]['tanggal'];
                                    break;
                                }
                            }
                        @endphp

                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="text-start">{{ $row['nama'] }}</td>
                            <td>{{ $row['alamat'] }}</td>
                            <td>{{ $row['peserta'] ?? 0 }}</td>
                            <td>{{ $lastTanggal }}</td>
                            <td>Rp {{ number_format($row['total'],0,',','.') }}</td>
                        </tr>
                        @php $totalPulasara += $row['total']; @endphp
                    @endforeach

                    <tr>
                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                        <td><strong>{{ $totalPesertaPulasara }}</strong></td>
                        <td></td>
                        <td><strong>Rp {{ number_format($totalPulasara,0,',','.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===========================
        4. LAPORAN PENGELUARAN
    ============================ --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white">
            <b>Laporan Pengeluaran</b>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; $totalKeluar=0; @endphp
                    @foreach($laporanPengeluaran as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $row->jenis }}</td>
                            <td>{{ $row->keterangan }}</td>
                            <td>Rp{{ number_format($row->jumlah,0,',','.') }}</td>
                        </tr>
                        @php $totalKeluar += $row->jumlah; @endphp
                    @endforeach
                </tbody>
            </table>
            <div class="fw-bold mt-2">
                Total Pengeluaran : Rp{{ number_format($totalKeluar,0,',','.') }}
            </div>
        </div>
    </div>

</div>
@endsection
