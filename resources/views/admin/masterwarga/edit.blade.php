@extends('layouts.app')

@section('content')
<div class="container">
    <h1>✏️ Edit Warga</h1>

    <form action="{{ route('admin.masterwarga.update', $masterwarga->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $masterwarga->nama }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">NIK</label>
            <input type="text" name="nik" class="form-control" value="{{ $masterwarga->nik }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control" value="{{ $masterwarga->tempat_lahir }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" class="form-control" value="{{ $masterwarga->tgl_lahir }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ $masterwarga->alamat }}</textarea>
        </div>

        <button class="btn btn-success">💾 Update</button>
        <a href="{{ route('admin.masterwarga.index') }}" class="btn btn-secondary">↩ Batal</a>
    </form>
</div>
@endsection
