<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('supplier.index');
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

    public function listData()
    {
        $supplier = Supplier::orderBy('id_supplier', 'desc')->get();
        $no = 0;
        $data = array();
        foreach ($supplier as $s) {
            $no++;
            $row = array();
            $row[] = $no;

            $row[] = $s->nama;
            $row[] = $s->alamat;
            $row[] = $s->telepon;
            $row[] = '<div class="btn-group">
            <a onclick="editForm(' . $s->id_supplier . ')" class="btn btn-primary btn-sm"> <i class="fa fa-pencil"></i> </a>
            <a onclick="deleteData(' . $s->id_supplier . ')" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </a>
           
            </div>';
            $data[] = $row;
        }
        $output = array("data" => $data);
        return response()->json($output);
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
        $supplier = new Supplier;
        $supplier->nama = $request['nama'];
        $supplier->alamat = $request['alamat'];
        $supplier->telepon = $request['telepon'];
        $supplier->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $supplier = Supplier::find($id);
        echo json_encode($supplier);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $supplier = Supplier::find($id);
        $supplier->nama = $request['nama'];
        $supplier->alamat = $request['alamat'];
        $supplier->telepon = $request['telepon'];
        $supplier->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $supplier = Supplier::find($id);
        $supplier->delete();
    }
}
