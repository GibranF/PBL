<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
    $belumDibayarCount = 0;

if (Auth::check() && Auth::user()->usertype === 'customer') {
    $belumDibayarCount = Transaksi::where('id_user', Auth::id())
        ->where('status_pembayaran', 'belum dibayar')
        ->count();
}

return view('halaman.landing-page', compact('belumDibayarCount'));

}
}
