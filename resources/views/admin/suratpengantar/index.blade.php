@extends('layouts.app')

@section('content')
<style>
    .table {
        font-size: 12px;
    }
    .table td, .table th {
        white-space: normal !important;
        word-wrap: break-word;
        vertical-align: middle;
    }
    .col-alamat {
        max-width: 180px;
    }
    .col-tujuan {
        max-width: 150px;
    }
    .btn-sm {
        white-space: nowrap;
        font-size: 10px;
        padding: 2px 6px;
    }
</style>

<div class="container">
    <h1>📚 Daftar Surat Pengantar</h1>

    <a href="{{ route('admin.suratpengantar.create') }}" class="btn btn-primary mb-3">➕ Tambah Surat</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h4>Jumlah Surat Masuk: {{ $totalSurat }}</h4>

    {{-- Pilih jumlah per halaman --}}
    <form method="GET" class="mb-3">
        <label for="perPage">Tampilkan per halaman:</label>
        <select name="perPage" id="perPage" onchange="this.form.submit()">
            @foreach([10,20,30,40,50] as $size)
                <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                    {{ $size }}
                </option>
            @endforeach
        </select>
    </form>
    
    {{-- Simple pagination (« » saja) --}}
    {{ $surat->appends(['perPage' => request('perPage', 10)])->links('pagination::simple-bootstrap-4') }}
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Tempat / Tgl Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Status</th>
                    <th>Warganegara</th>
                    <th>Agama</th>
                    <th>Pekerjaan</th>
                    <th class="col-alamat">Alamat</th>
                    <th class="col-tujuan">Tujuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surat as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($surat->currentPage()-1) * $surat->perPage() }}</td>
                        <td>{{ $item->warga->nama }}</td>
                        <td>{{ $item->warga->nik }}</td>
                        <td>{{ $item->warga->tempat_lahir }}, {{ $item->warga->tgl_lahir }}</td>
                        <td>{{ $item->jenis_kelamin }}</td>
                        <td>{{ $item->status_pernikahan }}</td>
                        <td>{{ $item->kewarganegaraan }}</td>
                        <td>{{ $item->agama }}</td>
                        <td>{{ $item->pekerjaan }}</td>
                        <td class="col-alamat">{{ $item->alamat }}</td>
                        <td class="col-tujuan">{{ $item->tujuan }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.suratpengantar.edit', $item->id) }}" 
                                class="btn btn-sm btn-warning">✏️</a>

                                <form action="{{ route('admin.suratpengantar.destroy', $item->id) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Hapus surat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">🗑</button>
                                </form>

                                <a href="{{ route('admin.suratpengantar.cetak', $item->id) }}" 
                                class="btn btn-sm btn-secondary" target="_blank">🖨</a>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr><td colspan="12" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Simple pagination (« » saja) --}}
    {{ $surat->appends(['perPage' => request('perPage', 10)])->links('pagination::simple-bootstrap-4') }}
</div>
@endsection
