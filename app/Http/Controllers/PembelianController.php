<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\PembelianDetail;
use Illuminate\Support\Facades\Redirect;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $supplier = Supplier::all();
        return view('pembelian.index', compact('supplier'));
    }


    public function listData()
    {
        $pembelian = Pembelian::leftJoin('supplier', 'supplier.id_supplier', '=', 'pembelian.id_supplier')
            ->orderBy('pembelian.id_pembelian', 'desc')->get();
        $no = 0;
        $data = array();
        foreach ($pembelian as $p) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = tanggal_indonesia(substr($p->created_at, 0, 10), false);
            $row[] = $p->nama;
            $row[] = $p->total_item;
            $row[] = "Rp. " . format_uang($p->total_harga);
            $row[] = $p->diskon . "%";
            $row[] = "Rp. " . format_uang($p->bayar);
            $row[] = '<div class="btn-group">
            <a onclick="showDetail(' . $p->id_pembelian . ')" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
            <a onclick="deleteData(' . $p->id_pembelian . ')" class="btn btn-danger"><i class="fa fa-trash"></i></a>
            </div>';
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
    public function create($id)
    {
        //
        $pembelian = new Pembelian;
        $pembelian->id_supplier = $id;
        $pembelian->total_item = 0;
        $pembelian->total_harga = 0;
        $pembelian->diskon = 0;
        $pembelian->bayar = 0;
        $pembelian->save();

        session(['idpembelian' => $pembelian->id_pembelian]);
        session(['idsupplier' => $id]);

        return Redirect::route('pembelian_detail.index');
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
        $pembelian = Pembelian::find($request['idpembelian']);

        // dd($pembelian);

        $pembelian->total_item = $request['totalitem'];
        $pembelian->total_harga = $request['total'];
        $pembelian->diskon = $request['diskon'];
        $pembelian->bayar = $request['bayar'];
        $pembelian->update();

        $detail = PembelianDetail::where('id_pembelian', '=', $request['idpembelian'])->get();
        foreach ($detail as $d) {
            $produk = Produk::where('kode_produk', '=', $d->kode_produk)->first();
            $produk->stok  += $d->jumlah;
            $produk->update();
        }

        return Redirect::route('pembelian.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pembelian  $pembelian
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $detail = PembelianDetail::leftJoin('produk', 'produk.kode_produk', '=', 'pembelian_detail.kode_produk')
            ->where('id_pembelian', '=', $id)->get();
        $no = 0;
        $data = array();
        foreach ($detail as $d) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $d->kode_produk;
            $row[] = $d->nama_produk;
            $row[] = "Rp. " . format_uang($d->harga_beli);
            $row[] = $d->jumlah;
            $row[] = "Rp. " . format_uang($d->harga_beli * $d->jumlah);
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pembelian  $pembelian
     * @return \Illuminate\Http\Response
     */
    public function edit(Pembelian $pembelian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pembelian  $pembelian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pembelian  $pembelian
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $pembelian = Pembelian::find($id);
        // dd($pembelian);
        $pembelian->delete();

        $detail = PembelianDetail::where('id_pembelian', '=', $id)->get();
        foreach ($detail as $d) {
            $produk = Produk::where('kode_produk', '=', $d->kode_produk)->first();
            $produk->stok -= $d->jumlah;
            $produk->update();
            $d->delete();
        }
    }
}
