{{-- resources/views/admin/pengeluaran/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">✏️ Edit Pengeluaran</h3>

    <form action="{{ route('admin.pengeluaran.update', $pengeluaran->id) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" 
                   value="{{ old('nama', $pengeluaran->nama) }}" required>
        </div>

        {{-- Tanggal Pengeluaran --}}
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" 
                   value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
        </div>

        {{-- Jenis --}}
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis</label>
            <select name="jenis" id="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Dansos" {{ $pengeluaran->jenis == 'Dansos' ? 'selected' : '' }}>Dansos</option>
                <option value="Pulasara" {{ $pengeluaran->jenis == 'Pulasara' ? 'selected' : '' }}>Pulasara</option>
                <option value="Iuran SK RT04" {{ $pengeluaran->jenis == 'Iuran SK RT04' ? 'selected' : '' }}>Iuran SK RT04</option>
                <option value="Sumbangan" {{ $pengeluaran->jenis == 'Sumbangan' ? 'selected' : '' }}>Sumbangan</option>
                <option value="Sumbangan RW05" {{ $pengeluaran->jenis == 'Sumbangan RW05' ? 'selected' : '' }}>Sumbangan RW05</option>
                <option value="Kas RT" {{ $pengeluaran->jenis == 'Kas RT' ? 'selected' : '' }}>Kas RT</option>
                <option value="Bank Adm" {{ $pengeluaran->jenis == 'Bank Adm' ? 'selected' : '' }}>Bank Adm</option>
                <option value="Posyandu" {{ $pengeluaran->jenis == 'Posyandu' ? 'selected' : '' }}>Posyandu</option>
                <option value="Posbindu" {{ $pengeluaran->jenis == 'Posbindu' ? 'selected' : '' }}>Posbindu</option>
            </select>
        </div>

        {{-- Jumlah --}}
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah (Rp)</label>
            <input type="number" name="jumlah" class="form-control" step="0.01" 
                   value="{{ old('jumlah', $pengeluaran->jumlah) }}" required>
        </div>

        {{-- Keterangan --}}
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
        </div>

        {{-- Bukti Bayar --}}
        <div class="mb-3">
            <label for="bukti_bayar" class="form-label">Bukti Bayar (gambar)</label>
            <input type="file" name="bukti_bayar" class="form-control" accept="image/*">
            <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
            @if($pengeluaran->bukti_bayar)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $pengeluaran->bukti_bayar) }}" 
                         alt="Bukti Bayar" style="max-width: 200px; height: auto;">
                </div>
            @endif
        </div>  

        {{-- Tombol Aksi --}}
        <button type="submit" class="btn btn-primary">💾 Update</button>
        <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
    </form>
</div>
@endsection
