<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

use App\Models\Produk;
use App\Models\Member;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\Redirect;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('penjualan.index');
    }

    public function listData()
    {
        $penjualan = Penjualan::leftJoin('users', 'users.id', '=', 'penjualan.id_user')
            ->select('users.*', 'penjualan.*', 'penjualan.created_at as tanggal')->orderBy('penjualan.id_penjualan', 'desc')
            ->get();
        $no = 0;
        $data = array();
        foreach ($penjualan as $p) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = tanggal_indonesia(substr($p->tanggal, 0, 10), false);
            $row[] = $p->kode_member;
            $row[] = $p->total_item;
            $row[] = "Rp. " . format_uang($p->total_harga);
            $row[] = $p->diskon . "%";
            $row[] = $p->name;
            $row[] = '<div class="btn-group">
            <a onclick="showDetail(' . $p->id_penjualan . ')" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
            <a onclick="deleteData(' . $p->id_penjualan . ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
           </div> ';
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $detail = PenjualanDetail::leftJoin('produk', 'produk.kode_produk', '=', 'penjualan_detail.kode_produk')
            ->where('id_penjualan', '=', $id)->get();
        $no = 0;
        $data = array();
        foreach ($detail as $d) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $d->kode_produk;
            $row[] = $d->nama_produk;
            $row[] = "Rp. " . format_uang($d->harga_jual);
            $row[] = $d->jumlah;
            $row[] = "Rp. " . format_uang($d->sub_total);
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $penjualan = Penjualan::find($id);
        $penjualan->delete();

        $detail = PenjualanDetail::where('id_penjualan', '=', $id)->get();
        foreach ($detail as $d) {
            $produk = Produk::where('kode_produk', '=', $d->kode_produk)->first();
            $produk->stok += $d->jumlah;
            $produk->update();
            $d->delete();
        }
    }
}
