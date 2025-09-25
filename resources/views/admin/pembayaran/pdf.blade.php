<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h3 { text-align: center; margin-bottom: 0; }
    </style>
</head>
<body>
    <h3>Laporan Pembayaran</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayarans as $pembayaran)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $pembayaran->jenis }}</td>
                <td>Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                <td>{{ $pembayaran->keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
