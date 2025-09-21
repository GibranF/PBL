@extends('layouts.admin')

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
</div>
@endsection



