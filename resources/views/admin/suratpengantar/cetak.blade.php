<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pengantar</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">SURAT PENGANTAR</h2>
    <hr>

    <table>
        <tr><td>Nama</td><td>: {{ $suratpengantar->warga->nama }}</td></tr>
        <tr><td>NIK</td><td>: {{ $suratpengantar->warga->nik }}</td></tr>
        <tr><td>Tempat/Tgl Lahir</td><td>: {{ $suratpengantar->warga->tempat_lahir }}, {{ $suratpengantar->warga->tgl_lahir }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>: {{ $suratpengantar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
        <tr><td>Status Pernikahan</td><td>: {{ $suratpengantar->status_pernikahan }}</td></tr>
        <tr><td>Kewarganegaraan</td><td>: {{ $suratpengantar->kewarganegaraan }}</td></tr>
        <tr><td>Agama</td><td>: {{ $suratpengantar->agama }}</td></tr>
        <tr><td>Pekerjaan</td><td>: {{ $suratpengantar->pekerjaan }}</td></tr>
        <tr><td>Alamat</td><td>: {{ $suratpengantar->alamat }}</td></tr>
        <tr><td>Maksud & Tujuan</td><td>: {{ $suratpengantar->tujuan }}</td></tr>
    </table>

    <br>
    Demikian surat Pengantar ini kami berikan guna proses tindak lanjut ke tingkat selanjutnya.
    <br>
    <table style="width: 100%; margin-top: 40px;">
    <tr>
        <td style="text-align: left; vertical-align: bottom;">
            <p>Ketua RW</p>
            <p style="margin-top: 80px;">_________________</p>
        </td>
        <td style="text-align: right; vertical-align: bottom;">
            <p>Tangerang, {{ now()->format('d-m-Y') }}</p>
            <p>Ketua RT</p>
            <p style="margin-top: 80px;">_________________</p>
        </td>
    </tr>
</table>

</div>

</div>

</body>
</html>
