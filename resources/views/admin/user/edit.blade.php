@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">✏️ Edit Pengguna</h3>
 
    <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label>Password <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small></label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="nohp" class="form-control" value="{{ $user->nohp }}" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="viewer" {{ $user->role == 'viewer' ? 'selected' : '' }}>Viewer</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">💾 Update</button>
        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">↩️ Kembali</a>
    </form>
</div>
@endsection
