<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Redirect;


class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('member.index');
    }

    public function listData()
    {
        $member = Member::orderBy('id_member', 'asc')->get();
        $no = 0;
        $data = array();
        foreach ($member as $m) {
            $no++;
            $row = array();
            $row[] = "<input type='checkbox' name='id[]' value='" . $m->id_member . "'>";
            $row[] = $no;
            $row[] = $m->kode_member;
            $row[] = $m->nama;
            $row[] = $m->alamat;
            $row[] = $m->telepon;
            $row[] = '
        <div class="btn-group">
        <a onclick="editForm(' . $m->id_member . ')" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
        <a onclick="deleteData(' . $m->id_member . ')" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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
        $jml = Member::where('kode_member', '=', $request['kode'])->count();
        if ($jml < 1) {
            $member = new Member;
            $member->kode_member = $request['kode_member'];
            $member->nama = $request['nama'];
            $member->alamat = $request['alamat'];
            $member->telepon = $request['telepon'];
            $member->save();
            echo json_encode(array('msg' => 'success'));
            return redirect('/member');
        } else {
            echo json_encode(array('msg' => 'error'));
            return redirect('/member');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $member = Member::find($id);
        echo json_encode($member);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $member = Member::find($id);
        $member->kode_member = $request['kode_member2'];
        $member->nama = $request['nama2'];
        $member->alamat = $request['alamat2'];
        $member->telepon = $request['telepon2'];
        $member->update();
        // echo json_encode(array('msg' => 'success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $member = Member::find($id);
        $member->delete();
    }

    public function printCard(Request $request)
    {
        $datamember = array();
        foreach ($request['id'] as $id) {
            $member = Member::find($id);
            $datamember[] = $member;
        }

        $pdf = Pdf::loadView('member.card', compact('datamember'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream();
    }
}
