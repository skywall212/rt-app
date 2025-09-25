@extends('layouts.app')

@section('title', 'Buat Surat Pengantar')

@section('content')
<div class="container mt-5">
    <h1>Buat Surat Pengantar</h1>

    <form action="{{ route('admin.suratpengantar.store') }}" method="POST">
        @csrf

        {{-- Search Master Warga --}}
        <div class="mb-3">
            <label for="search_warga" class="form-label">Cari Nama atau NIK:</label>
            <input type="text" class="form-control" id="search_warga" placeholder="Nama atau NIK">
        </div>

        {{-- Hidden field untuk master_warga_id --}}
        <input type="hidden" name="master_warga_id" id="master_warga_id">

        {{-- Nama --}}
        <div class="mb-3">
            <label class="form-label">Nama:</label>
            <input type="text" name="nama" class="form-control" id="nama" readonly required>
        </div>

        {{-- NIK --}}
        <div class="mb-3">
            <label class="form-label">NIK:</label>
            <input type="text" name="nik" class="form-control" id="nik" readonly required>
        </div>

        {{-- Tempat Lahir --}}
        <div class="mb-3">
            <label class="form-label">Tempat Lahir:</label>
            <input type="text" name="tempat" class="form-control" id="tempat" readonly required>
        </div>

        {{-- Tgl Lahir --}}
        <div class="mb-3">
            <label class="form-label">Tanggal Lahir:</label>
            <input type="date" name="tgl_lahir" class="form-control" id="tgl_lahir" readonly required>
        </div>

        {{-- Jenis Kelamin --}}
        <div class="mb-3">
            <label class="form-label">Jenis Kelamin:</label>
            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>

        {{-- Status Pernikahan --}}
        
        <div class="mb-3">
            <label class="form-label">Status Pernikahan:</label>
            <select name="status_pernikahan" id="status_pernikahan" class="form-control" required>
                <option value="">-- Pilih Status Pernikahan --</option>
                <option value="Belum Menikah">Belum Menikah</option>
                <option value="Menikah">Menikah</option>
                <option value="Duda">Duda</option>
                <option value="Janda">Janda</option>
                <option value="Lajang">Lajang</option>
            </select>
        </div>
        {{-- Kewarganegaraan --}}
        <label class="form-label">Kewarganegaraan:</label>
        <select name="kewarganegaraan" id="kewarganegaraan" class="form-control" required>
                <option value="">-- Pilih Kewarganegaraan --</option>
                <option value="WNI">WNI</option>
                <option value="WNA">WNA</option>
        </select>

        {{-- Agama --}}
        <label class="form-label">Agama:</label>
        <select name="agama" id="agama" class="form-control" required>
                <option value="">-- Pilih Agama --</option>
                <option value="Islam">Islam</option>
                <option value="Kristen">Kristen</option>
                <option value="Hindu">Hindu</option>
                <option value="Buddha">Buddha</option>
                <option value="Konghucu">Konghucu</option>
        </select>

        {{-- Pekerjaan --}}
        <label class="form-label">Pekerjaan:</label>
        <select name="pekerjaan" id="pekerjaan" class="form-control" required>
                <option value="">-- Pilih Pekerjaan --</option>
                <option value="PNS">PNS</option>
                <option value="Swasta">Swasta</option>
                <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                <option value="Mahasiswa">Mahasiswa</option>
                <option value="Pelajar">Pelajar</option>
        </select>

        {{-- Alamat --}}
        <div class="mb-3">
            <label class="form-label">Alamat:</label>
            <input type="text" name="alamat" class="form-control" id="alamat" readonly required>
        </div>

        {{-- Tujuan --}}
        <div class="mb-3">
            <label class="form-label">Tujuan:</label>
            <input type="text" name="tujuan" class="form-control" id="tujuan" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan Surat Pengantar</button>
    </form>

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    var typingTimer;
    var doneTypingInterval = 300; // delay 300ms

    $('#search_warga').on('keyup', function() {
        clearTimeout(typingTimer);
        var keyword = $(this).val().trim();
        if(keyword.length < 2){
            $('#master_warga_id, #nama, #nik, #tempat, #tgl_lahir, #jenis_kelamin, #status_pernikahan, #kewarganegaraan, #agama, #pekerjaan, #alamat').val('');
            return;
        }

        typingTimer = setTimeout(function() {
            $.ajax({
                url: '{{ route("admin.ajax.search-warga") }}',
                type: 'GET',
                dataType: 'json',
                data: { search: keyword },
                success: function(data) {
                    if(data.length > 0){
                        var item = data[0]; // ambil data pertama
                        $('#master_warga_id').val(item.id);
                        $('#nama').val(item.nama);
                        $('#nik').val(item.nik);
                        $('#tempat').val(item.tempat_lahir);
                        $('#tgl_lahir').val(item.tgl_lahir);
                        $('#jenis_kelamin').val(item.jenis_kelamin || '');
                        $('#status_pernikahan').val(item.status_pernikahan || '');
                        $('#kewarganegaraan').val(item.kewarganegaraan || '');
                        $('#agama').val(item.agama || '');
                        $('#pekerjaan').val(item.pekerjaan || '');
                        $('#alamat').val(item.alamat);
                    } else {
                        // kosongkan jika tidak ditemukan
                        $('#master_warga_id, #nama, #nik, #tempat, #tgl_lahir, #jenis_kelamin, #status_pernikahan, #kewarganegaraan, #agama, #pekerjaan, #alamat').val('');
                    }
                },
                error: function(xhr,status,error){
                    console.error(error);
                }
            });
        }, doneTypingInterval);
    });
});
</script>
@endsection
