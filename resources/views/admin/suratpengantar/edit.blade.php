@extends('layouts.app')

@section('content')
<div class="container">
    <h1>✏️ Edit Surat Pengantar</h1>

    <form action="{{ route('admin.suratpengantar.update', $suratpengantar->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="master_warga_id" class="form-label">Pilih Warga</label>
            <select name="master_warga_id" id="master_warga_id" class="form-control" required>
                <option value="">-- Pilih Warga --</option>
                @foreach($warga as $w)
                    <option value="{{ $w->id }}" {{ old('master_warga_id', $suratpengantar->master_warga_id) == $w->id ? 'selected' : '' }}>
                        {{ $w->nama }} - {{ $w->nik }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $suratpengantar->nama) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">NIK</label>
            <input type="text" name="nik" class="form-control" value="{{ old('nik', $suratpengantar->nik) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="tempat" class="form-control" value="{{ old('tempat', $suratpengantar->tempat_lahir) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $suratpengantar->tgl_lahir) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control" required>
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="L" {{ old('jenis_kelamin', $suratpengantar->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin', $suratpengantar->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status Pernikahan</label>
            <select name="status_pernikahan" class="form-control" required>
                <option value="">-- Pilih Status --</option>
                <option value="Belum Menikah" {{ old('status_pernikahan', $suratpengantar->status_pernikahan) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                <option value="Menikah" {{ old('status_pernikahan', $suratpengantar->status_pernikahan) == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                <option value="Cerai" {{ old('status_pernikahan', $suratpengantar->status_pernikahan) == 'Cerai' ? 'selected' : '' }}>Cerai</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Kewarganegaraan</label>
            <input type="text" name="kewarganegaraan" class="form-control" value="{{ old('kewarganegaraan', $suratpengantar->kewarganegaraan) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Agama</label>
            <input type="text" name="agama" class="form-control" value="{{ old('agama', $suratpengantar->agama) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pekerjaan</label>
            <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan', $suratpengantar->pekerjaan) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ old('alamat', $suratpengantar->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Tujuan</label>
            <textarea name="tujuan" class="form-control" required>{{ old('tujuan', $suratpengantar->tujuan) }}</textarea>
        </div>

        <button class="btn btn-success">💾 Update</button>
        <a href="{{ route('admin.suratpengantar.index') }}" class="btn btn-secondary">↩ Batal</a>
    </form>
</div>
@endsection
