@extends('layouts.owner')

@section('content')
<div class="container-fluid p-4">
    <h4 class="mb-4">📊 Laporan Pendapatan</h4>

    <!-- Filter -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('owner.laporan.laporan') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" id="start_date" name="start_date" 
                           value="{{ request('start_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date" 
                           value="{{ request('end_date') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="layanan" class="form-label">Layanan</label>
                    <select id="layanan" name="layanan" class="form-select">
                        <option value="">-- Semua Layanan --</option>
                        @foreach ($allLayanan as $l)
                            <option value="{{ $l->id_layanan }}" 
                                {{ request('layanan') == $l->id_layanan ? 'selected' : '' }}>
                                {{ $l->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                    <a href="{{ route('owner.laporan.laporan') }}" class="btn btn-outline-secondary flex-grow-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Pendapatan -->
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📑 Tabel Pendapatan</h5>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill"
                        data-bs-target="#daily">Harian</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill"
                        data-bs-target="#weekly">Mingguan</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill"
                        data-bs-target="#monthly">Bulanan</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill"
                        data-bs-target="#yearly">Tahunan</button></li>
            </ul>

            <div class="tab-content">
                <!-- Daily -->
                <div class="tab-pane fade show active" id="daily">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Rentang Waktu</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyLabels as $i => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ \Carbon\Carbon::parse($label)->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($dailyTotals[$i], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Weekly -->
                <div class="tab-pane fade" id="weekly">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Minggu Ke</th>
                                <th>Rentang Waktu</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weeklyLabels as $i => $label)
                                @php
                                    $range = explode(' - ', $label); 
                                @endphp
                                <tr>
                                    <td>Minggu {{ $i+1 }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($range[0])->format('d M Y') }} 
                                        - 
                                        {{ \Carbon\Carbon::parse($range[1])->format('d M Y') }}
                                    </td>
                                    <td>Rp {{ number_format($weeklyTotals[$i], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Monthly -->
                <div class="tab-pane fade" id="monthly">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Rentang Waktu</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyLabels as $i => $label)
                                @php
                                    $date = \Carbon\Carbon::parse($label . '-01');
                                @endphp
                                <tr>
                                    <td>{{ $date->format('F Y') }}</td>
                                    <td>{{ $date->startOfMonth()->format('d M Y') }} - {{ $date->endOfMonth()->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($monthlyTotals[$i], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Yearly -->
                <div class="tab-pane fade" id="yearly">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Rentang Waktu</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($yearlyLabels as $i => $label)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>01 Jan {{ $label }} - 31 Dec {{ $label }}</td>
                                    <td>Rp {{ number_format($yearlyTotals[$i], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
