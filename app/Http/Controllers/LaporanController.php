<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Carbon\Carbon;
use DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $start = $request->start_date;
        $end = $request->end_date;
        $layanan = $request->layanan;

        // Query dasar transaksi
        $transaksiQuery = Transaksi::query();

        // Kalau ada filter tanggal
        if ($start && $end) {
            $transaksiQuery->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
        }

        // Hitung pendapatan berdasarkan filter
        $todayIncome = (clone $transaksiQuery)->whereDate('created_at', Carbon::today())->sum('total');
        $weeklyIncome = (clone $transaksiQuery)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
        $monthlyIncome = (clone $transaksiQuery)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('total');
        $yearlyIncome = (clone $transaksiQuery)->whereYear('created_at', Carbon::now()->year)->sum('total');
        $totalSales = (clone $transaksiQuery)->sum('total');

        // Grafik Harian
        $daily = (clone $transaksiQuery)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->when($start && $end, fn($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $dailyLabels = $daily->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $dailyTotals = $daily->pluck('total');

        // Grafik Mingguan
        $weekly = (clone $transaksiQuery)
            ->select(DB::raw('YEARWEEK(created_at, 1) as week'), DB::raw('MIN(DATE(created_at)) as start_week'), DB::raw('MAX(DATE(created_at)) as end_week'), DB::raw('SUM(total) as total'))
            ->when($start && $end, fn($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]))
            ->groupBy('week')
            ->orderBy('week', 'ASC')
            ->get();

        $weeklyLabels = $weekly->map(fn($w) => $w->start_week . ' - ' . $w->end_week);
        $weeklyTotals = $weekly->pluck('total');

        // Grafik Bulanan
        $monthly = (clone $transaksiQuery)
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(total) as total'))
            ->when($start && $end, fn($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();

        $monthlyLabels = $monthly->pluck('month')->map(fn($m) => Carbon::parse($m . '-01')->format('M Y'));
        $monthlyTotals = $monthly->pluck('total');

        // Grafik Tahunan
        $yearly = (clone $transaksiQuery)
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('SUM(total) as total'))
            ->when($start && $end, fn($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]))
            ->groupBy('year')
            ->orderBy('year', 'ASC')
            ->get();

        $yearlyLabels = $yearly->pluck('year');
        $yearlyTotals = $yearly->pluck('total');

        // Pendapatan per layanan (pakai filter layanan kalau ada)
        $serviceIncome = DB::table('detail_transaksi as dt')
            ->join('layanan as l', 'dt.id_layanan', '=', 'l.id_layanan')
            ->join('transaksi as t', 'dt.id_transaksi', '=', 't.id_transaksi')
            ->select('l.nama_layanan', DB::raw('SUM(dt.subtotal) as total'))
            ->when($start && $end, fn($q) => $q->whereBetween(DB::raw('DATE(t.created_at)'), [$start, $end]))
            ->when($layanan, fn($q) => $q->where('l.id_layanan', $layanan))
            ->groupBy('l.nama_layanan')
            ->get();

        $serviceLabels = $serviceIncome->pluck('nama_layanan');
        $serviceTotals = $serviceIncome->pluck('total');

        // Semua layanan untuk dropdown filter
        $allLayanan = DB::table('layanan')->get();

        return view('owner.laporan.laporan', compact(
            'todayIncome',
            'weeklyIncome',
            'monthlyIncome',
            'yearlyIncome',
            'totalSales',
            'dailyLabels',
            'dailyTotals',
            'weeklyLabels',
            'weeklyTotals',
            'monthlyLabels',
            'monthlyTotals',
            'yearlyLabels',
            'yearlyTotals',
            'serviceLabels',
            'serviceTotals',
            'allLayanan'
        ));
    }

}
