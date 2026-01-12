@extends('layouts.app')

@section('content')
<div class="container">
    <h1>➕ Tambah Warga</h1>

    <form action="{{ route('admin.masterwarga.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">NIK</label>
            <input type="text" name="nik" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat (Contoh: G8/12)</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>

        <button class="btn btn-success">💾 Simpan</button>
        <a href="{{ route('admin.masterwarga.index') }}" class="btn btn-secondary">↩ Batal</a>
    </form>
</div>
@endsection
