@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <h4 class="mb-3">📊 Dashboard - Pembayaran dan Pengeluaran</h4>

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

    {{-- Charts Section --}}
    <div class="row mb-3">
        {{-- Grafik Pembayaran --}}
        <div class="col-lg-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-cash-coin me-2"></i>Grafik Pembayaran per Bulan
                    </h6>
                </div>
                <div class="card-body p-2">
                    <canvas id="pembayaranChart" height="250"></canvas>
                </div>
            </div>
        </div>

        {{-- Grafik Pengeluaran --}}
        <div class="col-lg-6 mb-3">
            <div class="card h-100">
                <div class="card-header bg-danger text-white py-2">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-cash-stack me-2"></i>Grafik Pengeluaran per Jenis
                    </h6>
                </div>
                <div class="card-body p-2">
                    <canvas id="pengeluaranChart" height="250"></canvas>
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

{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari Controller
    const labels = {!! json_encode($labels) !!};
    const dataSampahKeamanan = {!! json_encode($dataSampahKeamanan) !!};
    const dataDanaSosial = {!! json_encode($dataDanaSosial) !!};
    const dataPulasara = {!! json_encode($dataPulasara) !!};
    const dataSumbangan = {!! json_encode($dataSumbangan) !!};

    const dataDansos = {!! json_encode($dataDansos) !!};
    const dataPulasaraOut = {!! json_encode($dataPulasaraOut) !!};
    const dataIuranSKRT04 = {!! json_encode($dataIuranSKRT04) !!};
    const dataSumbanganOut = {!! json_encode($dataSumbanganOut) !!};
    const dataSumbanganRW05 = {!! json_encode($dataSumbanganRW05) !!};
    const dataKasRT = {!! json_encode($dataKasRT) !!};
    const dataBankAdm = {!! json_encode($dataBankAdm) !!};
    const dataPosyandu = {!! json_encode($dataPosyandu) !!};
    const dataPosbindu = {!! json_encode($dataPosbindu) !!};

    // Chart Pembayaran
    new Chart(document.getElementById('pembayaranChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Sampah Keamanan', data: dataSampahKeamanan, backgroundColor: 'rgba(34,197,94,0.8)' },
                { label: 'Dana Sosial', data: dataDanaSosial, backgroundColor: 'rgba(59,130,246,0.8)' },
                { label: 'Pulasara', data: dataPulasara, backgroundColor: 'rgba(234,179,8,0.8)' },
                { label: 'Sumbangan', data: dataSumbangan, backgroundColor: 'rgba(20,184,166,0.8)' }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Chart Pengeluaran
    new Chart(document.getElementById('pengeluaranChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Dansos', data: dataDansos, backgroundColor: 'rgba(239,68,68,0.8)' },
                { label: 'Pulasara', data: dataPulasaraOut, backgroundColor: 'rgba(168,85,247,0.8)' },
                { label: 'Iuran SK RT04', data: dataIuranSKRT04, backgroundColor: 'rgba(251,146,60,0.8)' },
                { label: 'Sumbangan', data: dataSumbanganOut, backgroundColor: 'rgba(14,165,233,0.8)' },
                { label: 'Sumbangan RW05', data: dataSumbanganRW05, backgroundColor: 'rgba(132,204,22,0.8)' },
                { label: 'Kas RT', data: dataKasRT, backgroundColor: 'rgba(202,138,4,0.8)' },
                { label: 'Bank Adm', data: dataBankAdm, backgroundColor: 'rgba(220,38,38,0.8)' },
                { label: 'Posyandu', data: dataPosyandu, backgroundColor: 'rgba(6,182,212,0.8)' },
                { label: 'Posbindu', data: dataPosbindu, backgroundColor: 'rgba(99,102,241,0.8)' }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endsection
