@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Dashboard Keuangan RT</h3>

    {{-- Filter Tahun --}}
    <div class="mb-3" style="max-width: 200px;">
        <form method="GET" action="{{ route('users.dashboard') }}">
            <label for="filter_tahun" class="form-label fw-bold mb-1" style="font-size: 0.9rem;">
                Pilih Tahun
            </label>
            <select id="filter_tahun" name="tahun" 
                    class="form-select form-select-sm" 
                    onchange="this.form.submit()">
                @php
                    $currentYear = date('Y');
                    $startYear = $currentYear - 5;
                @endphp
                @for ($year = $currentYear; $year >= $startYear; $year--)
                    <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endfor
            </select>
        </form>
    </div>


    {{-- Summary Cards --}}
    <div class="row mb-3">
        <div class="col-md-2 mb-2">
            <div class="card text-white bg-success h-100">
                <div class="card-body text-center p-2">
                    <h6 class="card-title mb-1 small">Sampah Keamanan</h6>
                    <h6 class="mb-0">Rp {{ number_format($totalSampahKeamanan, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="card text-white bg-primary h-100">
                <div class="card-body text-center p-2">
                    <h6 class="card-title mb-1 small">Dana Sosial</h6>
                    <h6 class="mb-0">Rp {{ number_format($totalDanaSosial, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="card text-white bg-warning h-100">
                <div class="card-body text-center p-2">
                    <h6 class="card-title mb-1 small">Pulasara</h6>
                    <h6 class="mb-0">Rp {{ number_format($totalPulasara, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="card text-white bg-info h-100">
                <div class="card-body text-center p-2">
                    <h6 class="card-title mb-1 small">Sumbangan</h6>
                    <h6 class="mb-0">Rp {{ number_format($totalSumbangan, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="card text-white bg-danger h-100">
                <div class="card-body text-center p-2">
                    <h6 class="card-title mb-1 small">Pengeluaran</h6>
                    <h6 class="mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <div class="card text-white bg-dark h-100">
                <div class="card-body text-center p-2">
                    <h6 class="card-title mb-1 small">Total User</h6>
                    <h6 class="mb-0">{{ $totalUser }} orang</h6>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik Pemasukan --}}
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    Grafik Pemasukan ({{ $tahun }})
                </div>
                <div class="card-body">
                    <canvas id="pemasukanChart" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- Grafik Pengeluaran --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    Grafik Pengeluaran ({{ $tahun }})
                </div>
                <div class="card-body">
                    <canvas id="pengeluaranChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    {{-- Summary Total --}}
    <div class="row">
        <div class="col-md-4 mb-2">
            <div class="card border-success">
                <div class="card-body text-center p-2">
                    <h6 class="text-success mb-1">Total Pemasukan</h6>
                    <h5 class="text-success mb-0">
                        Rp {{ number_format($totalSampahKeamanan + $totalDanaSosial + $totalPulasara + $totalSumbangan, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card border-danger">
                <div class="card-body text-center p-2">
                    <h6 class="text-danger mb-1">Total Pengeluaran</h6>
                    <h5 class="text-danger mb-0">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card {{ ($totalSampahKeamanan + $totalDanaSosial + $totalPulasara + $totalSumbangan - $totalPengeluaran) >= 0 ? 'border-success' : 'border-danger' }}">
                <div class="card-body text-center p-2">
                    <h6 class="{{ ($totalSampahKeamanan + $totalDanaSosial + $totalPulasara + $totalSumbangan - $totalPengeluaran) >= 0 ? 'text-success' : 'text-danger' }} mb-1">
                        Saldo
                    </h6>
                    <h5 class="{{ ($totalSampahKeamanan + $totalDanaSosial + $totalPulasara + $totalSumbangan - $totalPengeluaran) >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                        Rp {{ number_format($totalSampahKeamanan + $totalDanaSosial + $totalPulasara + $totalSumbangan - $totalPengeluaran, 0, ',', '.') }}
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ===== Data Pemasukan =====
    const pemasukan = @json($pemasukan);
    const pemasukanLabels = [...new Set(pemasukan.map(item => item.jenis))];

    const pemasukanData = pemasukanLabels.map(jenis => {
        let dataPerBulan = Array(12).fill(0);
        pemasukan.filter(item => item.jenis === jenis).forEach(item => {
            dataPerBulan[item.bulan - 1] = item.total;
        });
        return dataPerBulan;
    });

    const pemasukanColors = [
        '#007bff', // biru
        '#ffc107', // kuning
        '#28a745', // hijau
        '#6f42c1', // ungu
        '#20c997', // teal
        '#fd7e14', // oranye
    ];

    new Chart(document.getElementById('pemasukanChart'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: pemasukanLabels.map((jenis, idx) => ({
                label: jenis,
                data: pemasukanData[idx],
                backgroundColor: pemasukanColors[idx % pemasukanColors.length],
            }))
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // ===== Data Pengeluaran =====
    const pengeluaran = @json($pengeluaran);
    const pengeluaranLabels = [...new Set(pengeluaran.map(item => item.jenis))];

    const pengeluaranData = pengeluaranLabels.map(jenis => {
        let dataPerBulan = Array(12).fill(0);
        pengeluaran.filter(item => item.jenis === jenis).forEach(item => {
            dataPerBulan[item.bulan - 1] = item.total;
        });
        return dataPerBulan;
    });

    const pengeluaranColors = {
        'Dansos': '#dc3545',       // merah
        'Pulasara': '#fd7e14',     // oranye
        'Iuran SK RT04': '#20c997',// teal
        'Sumbangan': '#6610f2',    // ungu tua
        'Sumbangan RW05': '#e83e8c',// pink
        'Kas RT': '#17a2b8',       // cyan
        'Bank Adm': '#6c757d',     // abu
        'Posyandu': '#198754',     // hijau gelap
        'Posbindu': '#0d6efd',     // biru
    };

    new Chart(document.getElementById('pengeluaranChart'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: pengeluaranLabels.map(jenis => ({
                label: jenis,
                data: pengeluaranData[pengeluaranLabels.indexOf(jenis)],
                backgroundColor: pengeluaranColors[jenis] || '#999'
            }))
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
