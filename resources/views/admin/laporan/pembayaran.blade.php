@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">📄 Laporan Pembayaran</h3>

    {{-- Filter Bulan, Tahun & Jenis --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="bulan" class="form-select shadow-sm">
                <option value="">-- Pilih Bulan --</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <select name="tahun" class="form-select shadow-sm">
                <option value="">-- Pilih Tahun --</option>
                @for ($i = now()->year; $i >= 2020; $i--)
                    <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
            <select name="jenis" class="form-select shadow-sm">
                <option value="">-- Semua Jenis Pembayaran --</option>
                @foreach ($jenisList as $jenis)
                    <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    {{-- Tombol Export PDF --}}
    <div class="mb-3 text-end">
        <a href="{{ route('admin.laporan.pembayaran.pdf', request()->all()) }}" target="_blank" class="btn btn-danger">
            🧾 Export PDF
        </a>
    </div>

    {{-- Tabel Data --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-nowrap" style="font-family: Arial, sans-serif; font-size: 14px;">
            <thead>
                <tr class="text-center">
                    <th class="bg-primary text-white" style="width: 50px;">No</th>
                    <th class="bg-primary text-white" style="width: 130px;">Nama</th>
                    <th class="bg-primary text-white" style="width: 130px;">Tanggal Bayar</th>
                    <th class="bg-primary text-white" style="width: 130px;">Bulan </th>
                    <th class="bg-primary text-white" style="width: 220px;">Jenis</th>
                    <th class="bg-primary text-white" style="width: 150px;">Jumlah (Rp)</th>
                    <th class="bg-primary text-white" style="width: 300px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayaran as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->warga->nama ?? '-' }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $item->bulan_bayar }}</td>
                        <td>{{ $item->jenis }}</td>
                        <td class="text-end">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
