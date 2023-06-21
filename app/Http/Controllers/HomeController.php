<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\Member;
use App\Models\Penjualan;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // if (Auth::user()->level == 1) {
        //     return view('home.admin');
        // } else {
        //     return view('home.kasir');
        // }


        $setting = Setting::find(1);
        $awal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $akhir = date('Y-m-d');

        $tanggal = $awal;

        $data_tanggal = array();
        $data_pendapatan = array();

        while (strtotime($tanggal) <= strtotime($akhir)) {
            $data_tanggal[] = (int)substr($tanggal, 8, 2);
            $pendapatan = Penjualan::where('created_at', 'LIKE', "$tanggal%")->sum('bayar');

            $data_pendapatan[] = (int) $pendapatan;
            $tanggal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal)));
        }

        $kategori = Kategori::count();
        $produk = Produk::count();
        $supplier = Supplier::count();
        $member = Member::count();

        // dd($data_pendapatan);

        if (Auth::user()->level == 1) {
            return view('home.admin', compact('kategori', 'produk', 'supplier', 'member', 'awal', 'akhir', 'data_pendapatan', 'data_tanggal'));
        } else {
            return view('home.kasir', compact('setting'));
        }
    }
}
