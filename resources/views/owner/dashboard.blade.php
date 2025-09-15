@extends('layouts.owner')

@section('content')
<div class="row g-3">

    <!-- Total Pengguna -->
    <div class="col-md-3 col-sm-6">
        <div class="card statistics-card-1 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-bold text-muted">Total Pengguna</p>
                        <h4 class="mb-0 fw-bold text-secondary">{{ $totalUsers }}</h4>
                    </div>
                    <div class="avtar bg-light-secondary">
                        <i class="ti ti-users text-secondary f-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col-md-3 col-sm-6">
        <div class="card statistics-card-1 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-bold text-muted">Total Transaksi</p>
                        <h4 class="mb-0 fw-bold text-success">{{ $totalTransaksi }}</h4>
                    </div>
                    <div class="avtar bg-light-success">
                        <i class="ti ti-credit-card text-success f-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendapatan Hari Ini -->
    <div class="col-md-3 col-sm-6">
        <div class="card statistics-card-1 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-bold text-muted">Pendapatan Hari Ini</p>
                        <h4 class="mb-0 fw-bold text-info">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h4>
                    </div>
                    <div class="avtar bg-light-info">
                        <i class="ti ti-calendar-stats text-info f-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendapatan Minggu Ini -->
    <div class="col-md-3 col-sm-6">
        <div class="card statistics-card-1 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-bold text-muted">Pendapatan Minggu Ini</p>
                        <h4 class="mb-0 fw-bold text-teal">Rp {{ number_format($weeklyIncome, 0, ',', '.') }}</h4>
                    </div>
                    <div class="avtar bg-light-primary">
                        <i class="ti ti-calendar-event text-primary f-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendapatan Bulan Ini -->
    <div class="col-md-3 col-sm-6">
        <div class="card statistics-card-1 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-bold text-muted">Pendapatan Bulan Ini</p>
                        <h4 class="mb-0 fw-bold text-warning">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h4>
                    </div>
                    <div class="avtar bg-light-warning">
                        <i class="ti ti-calendar text-warning f-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendapatan Tahun Ini -->
    <div class="col-md-3 col-sm-6">
        <div class="card statistics-card-1 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-bold text-muted">Pendapatan Tahun Ini</p>
                        <h4 class="mb-0 fw-bold text-danger">Rp {{ number_format($yearlyIncome, 0, ',', '.') }}</h4>
                    </div>
                    <div class="avtar bg-light-danger">
                        <i class="ti ti-chart-line text-danger f-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pendapatan (Full Width) -->
    <div class="col-12">
        <div class="card statistics-card-1 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-bold text-muted">Total Pendapatan</p>
                        <h4 class="mb-0 fw-bold text-success">Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                    </div>
                    <div class="avtar bg-light-success">
                        <i class="ti ti-chart-bar text-success f-24"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Grafik Transaksi -->
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📊 Grafik Pendapatan</h5>
        <ul class="nav nav-pills" id="chart-tab" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill"
                    data-bs-target="#daily">Harian</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill"
                    data-bs-target="#weekly">Mingguan</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill"
                    data-bs-target="#monthly">Bulanan</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill"
                    data-bs-target="#yearly">Tahunan</button></li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <!-- Daily -->
            <div class="tab-pane fade show active" id="daily">
                <div class="chart-container" style="height: 320px;">
                    <canvas id="dailyChart"></canvas>
                </div>
                <div class="d-flex justify-content-end mt-3">
                </div>
            </div>

            <!-- Weekly -->
            <div class="tab-pane fade" id="weekly">
                <div class="chart-container" style="height: 320px;">
                    <canvas id="weeklyChart"></canvas>
                </div>
                <div class="d-flex justify-content-end mt-3">
                </div>
            </div>

            <!-- Monthly -->
            <div class="tab-pane fade" id="monthly">
                <div class="chart-container" style="height: 320px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
                <div class="d-flex justify-content-end mt-3">
                </div>
            </div>

            <!-- Yearly -->
            <div class="tab-pane fade" id="yearly">
                <div class="chart-container" style="height: 320px;">
                    <canvas id="yearlyChart"></canvas>
                </div>
                <div class="d-flex justify-content-end mt-3">
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const formatRupiah = (value) => {
        return 'Rp ' + (value || 0).toLocaleString('id-ID');
    };

    const createChart = (ctx, labels, data, label, type = 'line', color = '#7367f0', totalId) => {
        // gradient untuk line chart
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, color + '99');
        gradient.addColorStop(1, color + '00');

        // pastikan data array dikonversi ke angka
        const numericData = data.map((val) => Number(val) || 0);

        // hitung total
        const total = numericData.reduce((a, b) => a + b, 0);
        if (totalId && document.getElementById(totalId)) {
            document.getElementById(totalId).innerText = formatRupiah(total);
        }

        // cari nilai max untuk sumbu Y dan tambahkan buffer 20%
        const maxData = Math.max(...numericData, 0);
        const suggestedMax = maxData + (maxData * 0.15);

        return new Chart(ctx, {
            type,
            data: {
                labels,
                datasets: [{
                    label,
                    data: numericData,
                    borderColor: color,
                    backgroundColor: type === 'line' ? gradient : color,
                    fill: type === 'line',
                    tension: 0.4,
                    borderWidth: 2,
                    borderRadius: type === 'bar' ? 10 : 0,
                    pointBackgroundColor: color
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => formatRupiah(ctx.parsed.y || 0)
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#6c757d' },
                        grid: { display: false }
                    },
                    y: {
                        ticks: { callback: (val) => formatRupiah(val) },
                        grid: { color: '#f0f0f0' },
                        suggestedMax: suggestedMax 
                    }
                }
            }
        });
    };

    // Charts
    createChart(
        document.getElementById('dailyChart'),
        @json($dailyLabels),
        @json($dailyTotals),
        'Pendapatan Harian',
        'line',
        '#7367f0',
        'dailyTotal'
    );
    createChart(
        document.getElementById('weeklyChart'),
        @json($weeklyLabels),
        @json($weeklyTotals),
        'Pendapatan Mingguan',
        'line',
        '#28c76f',
        'weeklyTotal'
    );
    createChart(
        document.getElementById('monthlyChart'),
        @json($monthlyLabels),
        @json($monthlyTotals),
        'Pendapatan Bulanan',
        'line',
        '#ff9f43',
        'monthlyTotal'
    );
    createChart(
        document.getElementById('yearlyChart'),
        @json($yearlyLabels),
        @json($yearlyTotals),
        'Pendapatan Tahunan',
        'line',
        '#ea5455',
        'yearlyTotal'
    );
</script>
@endpush

