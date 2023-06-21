<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;

use Datatables;


class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pengeluaran.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function listData()
    {
        $pengeluaran = Pengeluaran::orderBy('id_pengeluaran', 'desc')->get();
        $no = 0;
        $data = array();
        foreach ($pengeluaran as $p) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = tanggal_indonesia(substr($p->created_at, 0, 10), false);
            $row[] = $p->jenis_pengeluaran;
            $row[] = "Rp. " . format_uang($p->nominal);
            $row[] = '<div class="btn-group">
            <a onclick="editForm(' . $p->id_pengeluaran . ')" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
            <a onclick="deleteData(' . $p->id_pengeluaran . ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
            </div>';
            $data[] = $row;
        }
        return Datatables::of($data)->escapeColumns([])->make(true);
    }

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
        $pengeluaran = new Pengeluaran;
        $pengeluaran->jenis_pengeluaran = $request['jenis'];
        $pengeluaran->nominal = $request['nominal'];
        $pengeluaran->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function show(Pengeluaran $pengeluaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $pengeluaran = Pengeluaran::find($id);
        echo json_encode($pengeluaran);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $pengeluaran = Pengeluaran::find($id);
        $pengeluaran->jenis_pengeluaran = $request['jenis2'];
        $pengeluaran->nominal = $request['nominal2'];
        $pengeluaran->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $pengeluaran = Pengeluaran::find($id);
        $pengeluaran->delete();
    }
}
