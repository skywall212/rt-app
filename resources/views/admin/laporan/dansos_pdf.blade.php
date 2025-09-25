<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dana Sosial {{ $tahun }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        h4, h6 { margin: 0; padding: 0; text-align: center; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 4px; text-align: center; }
        th { background: #007bff; color: white; font-size: 9px; }
        .tanggal-kecil { font-size: 7px; color: #555; display:block; }
        .nominal-bold { font-weight: bold; }
        .summary { margin-top: 15px; font-size: 10px; }
    </style>
</head>
<body>
    <h4>Pemasukan Dana Sosial Warga RT.04</h4>
    <h6>TAHUN {{ $tahun }}</h6>

    <table>
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
                    <td style="text-align:left;">{{ $item['nama'] }}</td>
                    <td style="text-align:left;">{{ $item['alamat'] }}</td>
                    <td>{{ $item['peserta'] }}</td>

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

                    <td>{{ $item['jumlah_bulan'] }}</td>
                    <td><strong>Rp{{ number_format($item['total'], 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="18" style="text-align:center;">Belum ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Total Peserta:</strong> {{ $totalPeserta }}</p>
        <p><strong>Total Pembayaran Dana Sosial:</strong> Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
    </div>
</body>
</html>
