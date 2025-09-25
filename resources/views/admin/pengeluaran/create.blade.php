@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">➕ Tambah Pengeluaran</h3>

    <form action="{{ route('admin.pengeluaran.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        {{-- Jenis --}}
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis</label>
            <select name="jenis" id="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Dansos" {{ old('jenis') == 'Dansos' ? 'selected' : '' }}>Dansos</option>
                <option value="Pulasara" {{ old('jenis') == 'Pulasara' ? 'selected' : '' }}>Pulasara</option>
                <option value="Iuran SK RT04" {{ old('jenis') == 'Iuran SK RT04' ? 'selected' : '' }}>Iuran SK RT04</option>
                <option value="Sumbangan" {{ old('jenis') == 'Sumbangan' ? 'selected' : '' }}>Sumbangan</option>
                <option value="Sumbangan RW05" {{ old('jenis') == 'Sumbangan RW05' ? 'selected' : '' }}>Sumbangan RW05</option>
                <option value="Kas RT" {{ old('jenis') == 'Kas RT' ? 'selected' : '' }}>Kas RT</option>
                <option value="Bank Adm" {{ old('jenis') == 'Bank Adm' ? 'selected' : '' }}>Bank Adm</option>
                <option value="Posyandu" {{ old('jenis') == 'Posyandu' ? 'selected' : '' }}>Posyandu</option>
                <option value="Posbindu" {{ old('jenis') == 'Posbindu' ? 'selected' : '' }}>Posbindu</option>
            </select>
        </div>
        

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah (Rp)</label>
            <input type="number" name="jumlah" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="bukti_bayar" class="form-label">Bukti Bayar</label>
            <input type="file" name="bukti_bayar" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
