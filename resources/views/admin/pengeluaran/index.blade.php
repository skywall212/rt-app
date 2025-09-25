{{-- resources/views/admin/pengeluaran/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0 d-flex justify-content-between align-items-center">
                📉 Daftar Pengeluaran
                <a href="{{ route('admin.pengeluaran.create') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Pengeluaran
                </a>
            </h4>
        </div>

        <div class="card-body">
            {{-- Alert --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Form Pencarian --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control" placeholder="Cari nama atau jenis...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
                @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
                @endif
                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan <strong>{{ $pengeluarans->firstItem() }}</strong> 
                        sampai <strong>{{ $pengeluarans->lastItem() }}</strong> 
                        dari <strong>{{ $pengeluarans->total() }}</strong> data
                    </div>
                    <div>
                        {{ $pengeluarans->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </form>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                            <th>Bukti Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluarans as $pengeluaran)
                        <tr>
                            <td>{{ ($pengeluarans->currentPage() - 1) * $pengeluarans->perPage() + $loop->iteration }}</td>
                            <td>{{ $pengeluaran->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</td>
                            <td><span class="badge bg-primary">{{ $pengeluaran->jenis }}</span></td>
                            <td class="text-danger fw-bold">
                                Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}
                            </td>
                            <td>{{ $pengeluaran->keterangan ?: '-' }}</td>
                            <td class="text-center">
                                @if($pengeluaran->bukti_bayar)
                                    <img src="{{ asset('storage/'.$pengeluaran->bukti_bayar) }}" 
                                         alt="Bukti Bayar" 
                                         class="img-thumbnail" 
                                         style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#buktiModal{{ $pengeluaran->id }}">

                                    <!-- Modal bukti bayar -->
                                    <div class="modal fade" id="buktiModal{{ $pengeluaran->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
                                            <div class="modal-content bg-transparent border-0" data-bs-dismiss="modal" style="cursor: pointer;">
                                                <div class="modal-body d-flex justify-content-center align-items-center p-0">
                                                    <img src="{{ asset('storage/'.$pengeluaran->bukti_bayar) }}" 
                                                         class="img-fluid rounded" 
                                                         style="max-height: 90vh; max-width: 90vw; object-fit: contain; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Belum upload</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.pengeluaran.edit', $pengeluaran->id) }}" 
                                       class="btn btn-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.pengeluaran.destroy', $pengeluaran->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data pengeluaran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan <strong>{{ $pengeluarans->firstItem() }}</strong> 
                    sampai <strong>{{ $pengeluarans->lastItem() }}</strong> 
                    dari <strong>{{ $pengeluarans->total() }}</strong> data
                </div>
                <div>
                    {{ $pengeluarans->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
