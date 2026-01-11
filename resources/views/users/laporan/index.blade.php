@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>
            <i class="bi bi-journal-text"></i>
            Laporan Keuangan Tahun {{ $tahun }}
        </h3>

        <div class="d-flex align-items-center">
            {{-- Filter Tahun --}}
            <form method="GET" action="{{ route('users.laporan.index') }}" class="me-2 d-flex align-items-center">
                <label for="filter_tahun" class="form-label fw-bold me-2 mb-0" style="font-size: 0.9rem;">
                    Tahun
                </label>
                <select id="filter_tahun" name="tahun"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()">
                    @php
                        $currentYear = date('Y');
                        $startYear = $currentYear - 5;
                    @endphp
                    @for ($year = $currentYear; $year >= $startYear; $year--)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endfor
                </select>
            </form>

            {{-- Tombol Export PDF --}}
            <a href="{{ route('users.laporan.pdf', ['tahun' => $tahun]) }}"
               class="btn btn-danger btn-sm" target="_blank">
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
                        @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'] as $b)
                            <th>{{ $b }}</th>
                        @endforeach
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; $totalSK=0; @endphp
                    @foreach($laporanSK as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row['nama'] }}</td>
                            <td>{{ $row['alamat'] }}</td>

                            @for($i=1; $i<=12; $i++)
                                @php $bulan = $row['bulan'][$i]; @endphp
                                <td>
                                    @if($bulan['jumlah'] > 0)
                                        Rp{{ number_format($bulan['jumlah'],0,',','.') }}<br>
                                        <small>{{ $bulan['tanggal'] }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endfor

                            <td><b>Rp {{ number_format($row['total'],0,',','.') }}</b></td>
                        </tr>
                        @php $totalSK += $row['total']; @endphp
                    @endforeach
                </tbody>
            </table>

            <div class="fw-bold mt-2">
                Total Pembayaran Sampah & Keamanan : Rp {{ number_format($totalSK,0,',','.') }}
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
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'] as $b)
                            <th>{{ $b }}</th>
                        @endforeach
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; $totalDansos=0; @endphp
                    @foreach($laporanDansos as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td class="text-start">{{ $row['nama'] }}</td>
                            <td class="text-start">{{ $row['alamat'] }}</td>

                            @for($i=1; $i<=12; $i++)
                                @php $bulan = $row['bulan'][$i]; @endphp
                                <td>
                                    @if($bulan['jumlah'] > 0)
                                        Rp{{ number_format($bulan['jumlah'],0,',','.') }}<br>
                                        <small class="text-muted">{{ $bulan['tanggal'] }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endfor

                            <td><b>Rp {{ number_format($row['total'],0,',','.') }}</b></td>
                        </tr>
                        @php $totalDansos += $row['total']; @endphp
                    @endforeach
                </tbody>
            </table>

            <div class="fw-bold mt-2">
                Total Pembayaran Dana Sosial : Rp {{ number_format($totalDansos,0,',','.') }}
            </div>
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
                            <td>{{ $row['nama'] }}</td>
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
                            <td>Rp {{ number_format($row->jumlah,0,',','.') }}</td>
                        </tr>
                        @php $totalKeluar += $row->jumlah; @endphp
                    @endforeach
                </tbody>
            </table>

            <div class="fw-bold mt-2">
                Total Pengeluaran : Rp {{ number_format($totalKeluar,0,',','.') }}
            </div>
        </div>
    </div>

    {{-- ===========================
        SUMMARY
    ============================ --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-danger text-white">
            <b>Total Informasi</b>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>Total Pemasukan</th>
                        <th>Total Pengeluaran</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalMasuk = $totalSampahKeamanan + $totalDanaSosial + $totalPulasara + $totalSumbangan;
                        $saldo = $totalMasuk - $totalPengeluaran;
                    @endphp
                    <tr>
                        <td class="text-success fw-bold">
                            Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                        </td>
                        <td class="text-danger fw-bold">
                            Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                        </td>
                        <td class="{{ $saldo >= 0 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                            Rp {{ number_format($saldo, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
