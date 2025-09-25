<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sampah Keamanan {{ $tahun }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #ddd; }
        td.left { text-align: left; }
        small { display: block; font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Laporan Sampah Keamanan Tahun {{ $tahun }}</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                @for($i=1; $i<=12; $i++)
                    <th>{{ \Carbon\Carbon::create()->month($i)->format('M') }}</th>
                @endfor
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="left">{{ $item['nama'] }}</td>
                <td class="left">{{ $item['alamat'] }}</td>
                @foreach($item['bulan'] as $bulan)
                    <td>
                        @if($bulan['jumlah'] > 0)
                            Rp {{ number_format($bulan['jumlah'],0,',','.') }}<br>
                            <small>{{ $bulan['tanggal'] }}</small>
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td>Rp {{ number_format($item['total'],0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Rekap di luar tabel --}}
    <div class="summary" style="margin-top: 20px;">
        <table style="border-collapse: collapse; width: auto;">
            <tr>
                <td style="width: 220px; text-align: left; padding: 4px 8px; font-weight: bold; border: none !important;">
                    Total Warga RT.04
                </td>
                <td style="width: 10px; text-align: center; padding: 4px 8px; border: none !important;">
                    :
                </td>
                <td style="text-align: left; padding: 4px 8px; border: none !important;">
                    {{ $totalPeserta }} orang
                </td>
            </tr>
            <tr>
                <td style="width: 220px; text-align: left; padding: 4px 8px; font-weight: bold; border: none !important;">
                    Total Pembayaran Sampah Keamanan
                </td>
                <td style="width: 10px; text-align: center; padding: 4px 8px; border: none !important;">
                    :
                </td>
                <td style="text-align: left; padding: 4px 8px; border: none !important;">
                    Rp{{ number_format($grandTotal, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
