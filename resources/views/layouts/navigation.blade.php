@php
    $user = Auth::user();
@endphp

<div class="bg-white border-end vh-100" style="width: 220px; font-size: 12px;">
    <div class="p-3 border-bottom text-center">
        <!--h5>Aplikasi RT</h5-->
        <a href="/">
            <img src="{{ asset('logo/logo-rt-kecil.png') }}" 
                 alt="Logo Aplikasi" 
                 class="mx-auto max-h-5">
        </a>
    </div>
    <ul class="nav flex-column p-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ $user->role == 'admin' ? route('admin.dashboard') : route('users.dashboard') }}">
                🏠 Dashboard
            </a>
        </li>

        @if ($user->role == 'admin')
        {{-- Menu Akses User --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">👥 Kelola Pengguna</a>
        </li>

        {{-- Menu Master Warga --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.masterwarga.index') }}">
                <i class="nav-icon fas fa-users"></i>
                <span>👥 Master Warga</span>
            </a>
        </li>

        {{-- Menu Administrasi --}}
        <li class="nav-item">
            <span class="nav-link">📚 Administrasi</span>
            <ul class="nav flex-column ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.suratpengantar.index') }}">✉️ Surat Pengantar</a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pembayaran.index') }}">💰 Pembayaran</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.pengeluaran.index') }}">📤 Pengeluaran</a>
        </li>

        {{-- Menu Laporan --}}
        <li class="nav-item">
            <span class="nav-link">📄 Laporan</span>
            <ul class="nav flex-column ms-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.laporan.pembayaran') }}">📊 Pembayaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.laporan.pengeluaran') }}">📉 Pengeluaran</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.laporan.sampahkeamanan') }}">
                        <i class="bi bi-trash-fill"></i> Sampah & Keamanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.laporan.pulasara') }}">
                        <i class="bi bi-heart-pulse-fill"></i> Pulasara
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.laporan.dansos') }}">
                        <i class="bi bi-people-fill"></i> Dansos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.laporan.umum') }}">
                        <i class="bi bi-people-fill"></i> Semua
                    </a>
                </li>
            </ul>
        </li>

        @elseif ($user->role == 'viewer')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.laporan.index') }}">📄 Laporan</a>
        </li>
        @endif

        <li class="nav-item mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link btn btn-link text-danger p-0" type="submit">🚪 Logout</button>
            </form>
        </li>
    </ul>
</div>
