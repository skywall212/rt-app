{{-- resources/views/admin/pembayaran/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">➕ Tambah Pembayaran</h3>

    {{-- Popup Modal Warning --}}
    @if(session('warning'))
    <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="warningModalLabel">⚠️ Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    {{ session('warning') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Pilih Warga --}}
        <div class="mb-3">
            <label for="warga_id" class="form-label">Nama Warga</label>
            <select name="warga_id" id="warga_id" class="form-select" required>
                <option value="">-- Pilih Warga --</option>
                @foreach($warga as $item)
                    <option value="{{ $item->id }}" {{ old('warga_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tanggal Pembayaran --}}
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
        </div>

        {{-- Jenis Pembayaran --}}
        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Pembayaran</label>
            <select name="jenis" id="jenis" class="form-select" required>
                <option value="">-- Pilih Jenis Pembayaran --</option>
                <option value="Sampah Keamanan" {{ old('jenis') == 'Sampah Keamanan' ? 'selected' : '' }}>Sampah Keamanan</option>
                <option value="Dana Sosial" {{ old('jenis') == 'Dana Sosial' ? 'selected' : '' }}>Dana Sosial</option>
                <option value="Pulasara" {{ old('jenis') == 'Pulasara' ? 'selected' : '' }}>Pulasara</option>
                <option value="Sumbangan" {{ old('jenis') == 'Sumbangan' ? 'selected' : '' }}>Sumbangan</option>
            </select>
        </div>

        {{-- Input Tambahan khusus Pulasara --}}
        <div id="pulasara-fields" style="display: none;">
            <div class="mb-3">
                <label for="peserta" class="form-label">Jumlah Peserta</label>
                <input type="number" name="peserta" id="peserta" class="form-control" min="1" value="{{ old('peserta') }}">
            </div>
            <div class="mb-3">
                <label for="jumlah_pulasara" class="form-label">Jumlah (Rp)</label>
                <input type="number" name="jumlah_pulasara" id="jumlah_pulasara" class="form-control" step="0.01" value="{{ old('jumlah_pulasara') }}">
            </div>
        </div>

        {{-- Jumlah umum (selain Pulasara) --}}
        <div id="jumlah-general" class="mb-3">
            <label for="jumlah_general" class="form-label">Jumlah (Rp)</label>
            <input type="number" name="jumlah_general" id="jumlah_general" class="form-control" step="0.01" value="{{ old('jumlah_general') }}">
        </div>

        {{-- Keterangan --}}
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
        </div>

        {{-- Bukti Bayar --}}
        <div class="mb-3">
            <label for="bukti_bayar" class="form-label">Bukti Bayar (gambar)</label>
            <input type="file" name="bukti_bayar" class="form-control" accept="image/*">
            <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
        </div>

        {{-- Tombol Aksi --}}
        <button type="submit" class="btn btn-success">💾 Simpan</button>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">⬅️ Kembali</a>
    </form>
</div>

{{-- Script untuk toggle input dan popup --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisSelect = document.getElementById('jenis');
        const pulasaraFields = document.getElementById('pulasara-fields');
        const jumlahGeneral = document.getElementById('jumlah-general');
        const pesertaInput = document.getElementById('peserta');
        const jumlahPulasaraInput = document.getElementById('jumlah_pulasara');

        function toggleFields() {
            if (jenisSelect.value === 'Pulasara') {
                pulasaraFields.style.display = 'block';
                jumlahGeneral.style.display = 'none';
                pesertaInput.required = true;
                jumlahPulasaraInput.required = true;
            } else {
                pulasaraFields.style.display = 'none';
                jumlahGeneral.style.display = 'block';
                pesertaInput.required = false;
                jumlahPulasaraInput.required = false;
            }
        }

        jenisSelect.addEventListener('change', toggleFields);
        toggleFields(); // Jalankan saat load pertama

        // Tampilkan modal warning jika ada session
        @if(session('warning'))
            var warningModal = new bootstrap.Modal(document.getElementById('warningModal'));
            warningModal.show();
        @endif
    });
</script>
@endsection
