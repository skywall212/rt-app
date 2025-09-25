@extends('layouts.app')

@section('content')
    <style>
        .table-blue thead tr th {
            background-color: #007bff;
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        .table-blue tbody tr:hover {
            background-color: #eaf3ff;
        }
    </style>

    <div class="container">
        <h3 class="mb-4">📄 Laporan Pengeluaran</h3>

        {{-- Filter Bulan & Tahun --}}
        <form method="GET" class="row g-3 mb-3">
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
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                {{-- Button Export PDF --}}
                <a href="{{ route('admin.laporan.pengeluaran.pdf', request()->query()) }}" class="btn btn-danger w-100">
                    Export PDF
                </a>
            </div>
        </form>

        {{-- Tabel Data --}}
        <table class="table table-bordered table-blue">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Jumlah (Rp)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengeluaran as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $item->jenis ?? '-' }}</td>
                        <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
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
@endsection
