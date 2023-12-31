<?php

namespace App\Http\Controllers;

use App\Models\PembelianDetail;
use Illuminate\Http\Request;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Produk;
use Illuminate\Support\Facades\Redirect;

class PembelianDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $produk = Produk::all();
        $idpembelian = session('idpembelian');
        $supplier = Supplier::find(session('idsupplier'));
        return view('pembelian_detail.index', compact('produk', 'idpembelian', 'supplier'));
    }

    public function listData($id)
    {
        $detail = PembelianDetail::leftJoin('produk', 'produk.kode_produk', '=', 'pembelian_detail.kode_produk')
            ->where('id_pembelian', '=', $id)->get();

        $no = 0;
        $data = array();
        $total = 0;
        $total_item = 0;
        foreach ($detail as $d) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $d->kode_produk;
            $row[] = $d->nama_produk;
            $row[] = "Rp. " . format_uang($d->harga_beli);
            $row[] = "<input type='number' class='form-control' name='jumlah $d->id_pembelian_detail' value='$d->jumlah' onChange='changeCount($d->id_pembelian_detail)'>";
            $row[] = "Rp. " . format_uang($d->harga_beli * $d->jumlah);
            $row[] = '<a onclick="deleteItem(' . $d->id_pembelian_detail . ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
            $data[] = $row;

            $total += $d->harga_beli * $d->jumlah;
            $total_item += $d->jumlah;
        }
        $data[] = array("<span class='hide total'>$total</span><span class='hide totalitem'>$total_item</span>", "", "", "", "", "", "");

        $output = array("data" => $data);
        // dd($output);
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
        $produk = Produk::where('kode_produk', '=', $request['kode'])->first();
        $detail = new PembelianDetail;
        $detail->id_pembelian = $request['idpembelian'];
        $detail->kode_produk = $request['kode'];
        $detail->harga_beli = $produk->harga_beli;
        $detail->jumlah = 1;
        $detail->sub_total = $produk->harga_beli;
        $detail->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PembelianDetail  $pembelianDetail
     * @return \Illuminate\Http\Response
     */
    public function show(PembelianDetail $pembelianDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PembelianDetail  $pembelianDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(PembelianDetail $pembelianDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PembelianDetail  $pembelianDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $nama_input = "jumlah_" . $id;
        $detail = PembelianDetail::find($id);
        $detail->jumlah = $request[$nama_input];
        $detail->sub_total = $detail->harga_beli * $request[$nama_input];
        $detail->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PembelianDetail  $pembelianDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $detail = PembelianDetail::find($id);
        $detail->delete();
    }

    public function loadForm($diskon, $total)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $data = array(
            "totalrp" => format_uang($total),
            "bayar" => $bayar,
            "bayarrp" => format_uang($bayar),
            "terbilang" => ucwords(terbilang($bayar)) . "Rupiah"
        );

        // dd($data);

        return response()->json($data);
    }
}
