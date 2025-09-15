<?php
namespace App\View\Components;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Navbar extends Component
{
    public $belumDibayarCount;

    public function __construct()
    {
        $this->belumDibayarCount = Auth::check() && Auth::user()->usertype === 'customer'
            ? Transaksi::where('id_user', Auth::id())
                ->where('status_pembayaran', 'belum dibayar')
                ->count()
            : 0;
    }

    public function render()
    {
        return view('components.navbar');
    }
}