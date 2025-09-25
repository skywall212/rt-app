<div class="mb-3">
    <label>Nama</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label>NIP</label>
    <input type="text" name="nip" class="form-control" value="{{ old('nip', $user->nip ?? '') }}">
</div>

<div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-select" required>
        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="viewer" {{ old('role', $user->role ?? '') == 'viewer' ? 'selected' : '' }}>Viewer</option>
    </select>
</div>

<div class="mb-3">
    <label>Password</label>
    <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
</div>

<div class="mb-3">
    <label>Konfirmasi Password</label>
    <input type="password" name="password_confirmation" class="form-control" {{ isset($user) ? '' : 'required' }}>
</div>

<button type="submit" class="btn btn-success">{{ $submit }}</button>
<a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
