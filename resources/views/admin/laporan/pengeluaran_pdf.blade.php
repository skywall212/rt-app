<h3 style="text-align:center;">Laporan Pengeluaran</h3>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
    <thead>
        <tr style="background-color:#007bff; color:white;">
            <th>No</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Jumlah (Rp)</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pengeluaran as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->jenis ?? '-' }}</td>
                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
