{{-- resources/views/admin/laporan/umum_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Tahun {{ $tahun }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h3 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 3px; text-align: center; }
        th { background-color: #e0e0e0; }
        .section-title {
            font-weight: bold;
            margin: 8px 0 4px;
            padding: 4px;
            background: #ddd;
        }
        .summary { font-weight: bold; margin-top: 5px; }
        .text-end { text-align: right; }
        .tanggal-kecil { font-size: 7px; color: #555; display: block; }
    </style>
</head>
<body>

    <h3>Laporan Semester Tahun {{ $tahun }}</h3>

         
    {{-- ===========================
        1. LAPORAN SAMPAH & KEAMANAN
    ============================ --}}
    <div class="section-title">Pemasukan Sampah & Keamanan</div>
    <table border="1" cellspacing="0" cellpadding="4" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                @for($i=1;$i<=12;$i++)
                    <th>{{ strtoupper(date('M', mktime(0,0,0,$i,1))) }}</th>
                @endfor
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; $totalSK=0; @endphp
            @foreach($laporanSK as $row)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td class="text-start">{{ $row['nama'] }}</td>
                    <td class="text-start">{{ $row['alamat'] }}</td>

                    {{-- Loop bulan 1 - 12 --}}
                    @for($i=1;$i<=12;$i++)
                        <td>
                            @if(!empty($row['bulan'][$i]['jumlah']) && $row['bulan'][$i]['jumlah'] > 0)
                                Rp{{ number_format($row['bulan'][$i]['jumlah'],0,',','.') }}<br>
                                <small style="font-size: 10px; color:#666;">
                                    {{ $row['bulan'][$i]['tanggal'] ?? '' }}
                                </small>
                            @else
                                -
                            @endif
                        </td>
                    @endfor

                    <td><b>Rp{{ number_format($row['total'],0,',','.') }}</b></td>
                </tr>
                @php $totalSK += $row['total']; @endphp
            @endforeach
        </tbody>
    </table>

    <div class="summary" style="margin-top:10px; font-weight:bold;">
        Total Pembayaran Sampah & Keamanan : Rp{{ number_format($totalSK,0,',','.') }}
    </div>
    <br>    
    {{-- ===========================
        2. LAPORAN DANA SOSIAL
    ============================ --}}
    <div class="section-title">Pemasukan Dana Sosial</div>
    <table border="1" cellspacing="0" cellpadding="4" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                @for($i=1;$i<=12;$i++)
                    <th>{{ strtoupper(date('M', mktime(0,0,0,$i,1))) }}</th>
                @endfor
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
                    
                    {{-- Loop bulan 1 - 12 --}}
                    @for($i = 1; $i <= 12; $i++)
                        <td>
                            @if(!empty($row['bulan'][$i]['jumlah']) && $row['bulan'][$i]['jumlah'] > 0)
                                Rp{{ number_format($row['bulan'][$i]['jumlah'], 0, ',', '.') }}<br>
                                <small>
                                    @if(!empty($row['bulan'][$i]['tanggal']) && \Carbon\Carbon::hasFormat($row['bulan'][$i]['tanggal'], 'Y-m-d'))
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $row['bulan'][$i]['tanggal'])->format('d/m/Y') }}
                                    @else
                                        {{ $row['bulan'][$i]['tanggal'] ?? '-' }}
                                    @endif
                                </small>
                            @else
                                -
                            @endif
                        </td>
                    @endfor

                    <td><b>Rp{{ number_format($row['total'],0,',','.') }}</b></td>
                   
                </tr>
                @php $totalDansos += $row['total']; @endphp
            @endforeach
        </tbody>
        
    </table>

    <div class="summary" style="margin-top:10px; font-weight:bold;">
        Total Pembayaran Dana Sosial : Rp{{ number_format($totalDansos,0,',','.') }}
    </div>

    <br>
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
    <div class="section-title">Laporan Pengeluaran</div>
    <table>
        <thead>
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
                    <td class="text-start">{{ $row->keterangan }}</td>
                    <td>Rp{{ number_format($row->jumlah,0,',','.') }}</td>
                </tr>
                @php $totalKeluar += $row->jumlah; @endphp
            @endforeach
        </tbody>
    </table>
    <div class="summary">
        Total Pengeluaran : Rp{{ number_format($totalKeluar,0,',','.') }}
    </div>

    {{-- Summary Total dalam Tabel --}}
    <div class="table-responsive mt-3">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>Total Pemasukan</th>
                    <th>Total Pengeluaran</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-success fw-bold">
                        Rp {{ number_format($totalSampahKeamanan + $totalDanaSosial + $totalPulasara + $totalSumbangan, 0, ',', '.') }}
                    </td>
                    <td class="text-danger fw-bold">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </td>
                    <td class="{{ ($totalSampahKeamanan + $totalDanaSosial + $totalPulasara + $totalSumbangan - $totalPengeluaran) >= 0 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                        Rp {{ number_format($totalSampahKeamanan + $totalDanaSosial + $totalPulasara + $totalSumbangan - $totalPengeluaran, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
