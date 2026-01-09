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
                    <option value="{{ $item->id }}"
                        {{ old('warga_id', $pembayaran->warga_id) == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tanggal Pembayaran --}}
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ old('tanggal', \Carbon\Carbon::parse($pembayaran->tanggal)->format('Y-m-d')) }}"
                   required>
        </div>

        {{-- Bulan Pembayaran --}}
        <div class="mb-3">
            <label for="bulan_bayar" class="form-label">Bulan</label>
            <select name="bulan_bayar" id="bulan_bayar" class="form-select" required>
                <option value="">-- Pilih Bulan --</option>
                @foreach ([
                    'Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'
                ] as $bulan)
                    <option value="{{ $bulan }}"
                        {{ old('bulan_bayar', $pembayaran->bulan_bayar) == $bulan ? 'selected' : '' }}>
                        {{ $bulan }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Jenis Pembayaran --}}
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Pembayaran</label>
            <select name="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis Pembayaran --</option>
                @foreach (['Sampah Keamanan','Dana Sosial','Pulasara','Sumbangan'] as $jenis)
                    <option value="{{ $jenis }}"
                        {{ old('jenis', $pembayaran->jenis) == $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Peserta (khusus Pulasara) --}}
        <div class="mb-3">
            <label for="peserta" class="form-label">Jumlah Peserta (khusus Pulasara)</label>
            <input type="number" name="peserta" class="form-control"
                   value="{{ old('peserta', $pembayaran->peserta) }}"
                   min="1">
        </div>

        {{-- Jumlah --}}
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah (Rp)</label>
            <input type="number" name="jumlah" class="form-control"
                   value="{{ old('jumlah', $pembayaran->jumlah) }}"
                   min="0" required>
        </div>

        {{-- Keterangan --}}
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $pembayaran->keterangan) }}</textarea>
        </div>

        {{-- Bukti Bayar --}}
        <div class="mb-3">
            <label for="bukti_bayar" class="form-label">Bukti Bayar</label>
            <input type="file" name="bukti_bayar" class="form-control" accept="image/*">
            <small class="text-muted">Format JPG/PNG, maksimal 2MB</small>

            @if($pembayaran->bukti_bayar)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $pembayaran->bukti_bayar) }}"
                         alt="Bukti Bayar" class="img-thumbnail" style="max-width:200px">
                </div>
            @endif
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-primary">💾 Update</button>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
    </form>
</div>
@endsection
