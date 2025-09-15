<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $totalUsers = User::count();
        $totalTransaksi = Transaksi::count();
        $totalSales = Transaksi::sum('total');

        // Pendapatan Hari Ini & Bulan Ini
        $todayIncome = Transaksi::whereDate('tanggal', Carbon::today())->sum('total');
        $monthlyIncome = Transaksi::whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->sum('total');

        // Grafik Harian (7 hari terakhir)
        $dailyData = Transaksi::selectRaw('DATE(tanggal) as tgl, SUM(total) as total')
            ->where('tanggal', '>=', Carbon::now()->subDays(6))
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get();

        $dailyLabels = [];
        $dailyTotals = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = Carbon::parse($date)->format('d M');
            $found = $dailyData->firstWhere('tgl', $date);
            $dailyTotals[] = $found ? $found->total : 0;
        }

        // Grafik Mingguan (8 minggu terakhir)
        $weeklyData = Transaksi::selectRaw('YEARWEEK(tanggal, 1) as minggu, SUM(total) as total')
            ->where('tanggal', '>=', Carbon::now()->subWeeks(7)->startOfWeek())
            ->groupBy('minggu')
            ->orderBy('minggu')
            ->get();

        $weeklyLabels = [];
        $weeklyTotals = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            $weeklyLabels[] = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');
            $weekNum = $weekStart->format('oW'); // format YearWeek
            $found = $weeklyData->firstWhere('minggu', $weekStart->format('oW'));
            $weeklyTotals[] = $found ? $found->total : 0;
        }

        // Grafik Bulanan (12 bulan terakhir)
        $monthlyData = Transaksi::selectRaw('YEAR(tanggal) as th, MONTH(tanggal) as bln, SUM(total) as total')
            ->where('tanggal', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('th', 'bln')
            ->orderBy('th')
            ->orderBy('bln')
            ->get();

        $monthlyLabels = [];
        $monthlyTotals = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');
            $found = $monthlyData->first(function ($item) use ($date) {
                return $item->th == $date->year && $item->bln == $date->month;
            });
            $monthlyTotals[] = $found ? $found->total : 0;
        }
        // Pendapatan Minggu Ini
        $weeklyIncome = Transaksi::whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('total');

        // Pendapatan Tahun Ini
        $yearlyIncome = Transaksi::whereYear('tanggal', Carbon::now()->year)
            ->sum('total');


        // Grafik Tahunan (5 tahun terakhir)
        $yearlyData = Transaksi::selectRaw('YEAR(tanggal) as th, SUM(total) as total')
            ->where('tanggal', '>=', Carbon::now()->subYears(4)->startOfYear())
            ->groupBy('th')
            ->orderBy('th')
            ->get();

        $yearlyLabels = [];
        $yearlyTotals = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = Carbon::now()->subYears($i)->year;
            $yearlyLabels[] = $year;
            $found = $yearlyData->firstWhere('th', $year);
            $yearlyTotals[] = $found ? $found->total : 0;
        }

        return view('owner.dashboard', compact(
            'totalUsers',
            'totalTransaksi',
            'totalSales',
            'todayIncome',
            'weeklyIncome',
            'monthlyIncome',
            'yearlyIncome',
            'dailyLabels',
            'dailyTotals',
            'weeklyLabels',
            'weeklyTotals',
            'monthlyLabels',
            'monthlyTotals',
            'yearlyLabels',
            'yearlyTotals'
        ));
    }
}
