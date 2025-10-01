@extends('layouts.app')

@section('content')
<div class="container">
    <h1>📋 Daftar Master Warga</h1>

    <a href="{{ route('admin.masterwarga.create') }}" class="btn btn-primary mb-3">➕ Tambah Warga</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mt-3">
    {{ $warga->links('pagination::bootstrap-4') }}
    </div>



    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Tempat / Tgl Lahir</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($warga as $w)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $w->nama }}</td>
                    <td>{{ $w->nik }}</td>
                    <td>{{ $w->tempat_lahir }}, {{ $w->tgl_lahir }}</td>
                    <td>{{ $w->alamat }}</td>
                    <td>
                        <a href="{{ route('admin.masterwarga.edit', $w->id) }}" class="btn btn-sm btn-warning">✏️ Edit</a>
                        <form action="{{ route('admin.masterwarga.destroy', $w->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus data warga ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑 Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
