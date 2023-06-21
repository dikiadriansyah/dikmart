<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Datatables;
use PDF;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $kategori = Kategori::all();
        return view('produk.index', compact('kategori'));
    }


    public function listData()
    {
        $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', '=', 'produk.id_kategori')->orderBy('produk.id_produk', 'desc')->get();
        $no = 0;
        $data = array();
        foreach ($produk as $p) {
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' name='id[]' value='" . $p->id_produk . "'>";
            $row[] = $no;
            $row[] = $p->kode_produk;
            $row[] = $p->nama_produk;
            $row[] = $p->nama_kategori;
            $row[] = $p->merk;
            $row[] = "Rp. " . format_uang($p->harga_beli);
            $row[] = "Rp. " . format_uang($p->harga_jual);
            $row[] = $p->diskon . "%";
            $row[] = $p->stok;
            $row[] = "
            <div class='btn-group'>
            <a onclick='editForm(" . $p->id_produk . ")' class='btn btn-primary btn-sm'><i class='fa fa-pencil'></i></a>
          <a onclick='deleteData(" . $p->id_produk . ")' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i></a>
          
            </div> ";
            $data[] = $row;
        }
        return Datatables::of($data)->escapeColumns([])->make(true);
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
        $jml = Produk::where('kode_produk', '=', $request['kode'])->count();
        // dd($jml);
        if ($jml < 1) {
            $produk = new Produk;
            $produk->kode_produk = $request['kode'];
            $produk->nama_produk = $request['nama'];
            $produk->id_kategori = $request['kategori'];
            $produk->merk = $request['merk'];
            $produk->harga_beli = $request['harga_beli'];
            $produk->diskon = $request['diskon'];
            $produk->harga_jual = $request['harga_jual'];
            $produk->stok = $request['stok'];
            $produk->save();
            echo json_encode(array('msg' => 'success'));
        } else {
            echo json_encode(array('msg' => 'error'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $produk = Produk::find($id);
        echo json_encode($produk);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $produk = Produk::find($id);
        $produk->nama_produk = $request['nama'];
        $produk->id_kategori = $request['kategori'];
        $produk->merk = $request['merk'];
        $produk->harga_beli = $request['harga_beli'];
        $produk->diskon = $request['diskon'];
        $produk->harga_jual = $request['harga_jual'];
        $produk->stok = $request['stok'];
        $produk->update();
        echo json_encode(array('msg' => 'success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $produk = Produk::find($id);
        $produk->delete();
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request['id'] as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }
    }

    public function printBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request['id'] as $id) {
            $produk = Produk::find($id);
            $dataproduk[] = $produk;
        }

        $no = 1;
        $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream();
    }
}
