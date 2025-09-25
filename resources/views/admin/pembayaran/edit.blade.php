{{-- resources/views/admin/pembayaran/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">✏️ Edit Pembayaran</h3>

    <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Pilih Warga --}}
        <div class="mb-3">
            <label for="warga_id" class="form-label">Nama Warga</label>
            <select name="warga_id" id="warga_id" class="form-select" required>
                <option value="">-- Pilih Warga --</option>
                @foreach($warga as $item)
                    <option value="{{ $item->id }}" {{ $pembayaran->warga_id == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }} 
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tanggal Pembayaran --}}
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" 
                   value="{{ old('tanggal', $pembayaran->tanggal) }}" required>
        </div>

        {{-- Jenis Pembayaran --}}
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Pembayaran</label>
            <select name="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis Pembayaran --</option>
                <option value="Sampah Keamanan" {{ $pembayaran->jenis == 'Sampah Keamanan' ? 'selected' : '' }}>Sampah Keamanan</option>
                <option value="Dana Sosial" {{ $pembayaran->jenis == 'Dana Sosial' ? 'selected' : '' }}>Dana Sosial</option>
                <option value="Pulasara" {{ $pembayaran->jenis == 'Pulasara' ? 'selected' : '' }}>Pulasara</option>
                <option value="Sumbangan" {{ $pembayaran->jenis == 'Sumbangan' ? 'selected' : '' }}>Sumbangan</option>
            </select>
        </div>

        {{-- Jumlah --}}
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah (Rp)</label>
            <input type="number" name="jumlah" class="form-control" step="0.01" 
                   value="{{ old('jumlah', $pembayaran->jumlah) }}" required>
        </div>

        
        {{-- Keterangan --}}
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $pembayaran->keterangan) }}</textarea>
        </div>

        {{-- Bukti Bayar --}}
        <div class="mb-3">
            <label for="bukti_bayar" class="form-label">Bukti Bayar (gambar)</label>
            <input type="file" name="bukti_bayar" class="form-control" accept="image/*">
            <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
            @if($pembayaran->bukti_bayar)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" 
                         alt="Bukti Bayar" style="max-width: 200px; height: auto;">
                </div>
            @endif
        </div>  

        {{-- Tombol Aksi --}}
        <button type="submit" class="btn btn-primary">💾 Update</button>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
    </form>
</div>
@endsection
