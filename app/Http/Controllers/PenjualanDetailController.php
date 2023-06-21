<?php

namespace App\Http\Controllers;

use App\Models\PenjualanDetail;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
// use Barryvdh\DomPDF\PDF;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Member;
use App\Models\Setting;
use Illuminate\Support\Facades\Redirect;
use PDF;

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;


class PenjualanDetailController extends Controller
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
        $member = Member::all();
        $setting = Setting::first();

        // mengecek apakah ada transaksi yg sedang berjalan
        if (!empty(session('idpenjualan'))) {
            $idpenjualan = session('idpenjualan');
            $id_user = session('id_user');
            return view('penjualan_detail.index', compact('produk', 'member', 'setting', 'idpenjualan', 'id_user'));
        } else {
            return Redirect::route('home');
        }
    }

    public function listData($id)
    {
        $detail = PenjualanDetail::leftJoin('produk', 'produk.kode_produk', '=', 'penjualan_detail.kode_produk')->where('id_penjualan', '=', $id)->get();
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
            $row[] = "Rp. " . format_uang($d->harga_jual);
            $row[] = "<input type='number' class='form-control' name='jumlah_$d->id_penjualan_detail' value='$d->jumlah' onChange='changeCount($d->id_penjualan_detail)'>";
            $row[] = $d->diskon;
            $row[] = "Rp. " . format_uang($d->sub_total);
            $row[] = '<div class="btn-group">
        <a onclick="deleteItem(' . $d->id_penjualan_detail . ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
        </div>';
            $data[] = $row;
            $total += $d->harga_jual * $d->jumlah;
            $total_item += $d->jumlah;
        }

        // membuat hidden data untuk keperluan perhitungan total dan total item
        $data[] = array("
<span class='hide total'>$total</span>
<span class='hide totalitem'>$total_item</span>", "", "", "", "", "", "", "");
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
        $produk = Produk::where('kode_produk', '=', $request['kode'])->first();

        $detail = new PenjualanDetail;
        $detail->id_penjualan = $request['idpenjualan'];
        $detail->kode_produk = $request['kode'];
        $detail->harga_jual = $produk->harga_jual;
        $detail->jumlah = 1;
        $detail->diskon = $produk->diskon;

        // sub total disesuaikan dengan diskon
        $detail->sub_total = $produk->harga_jual - ($produk->diskon / 100 * $produk->harga_jual);
        $detail->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PenjualanDetail  $penjualanDetail
     * @return \Illuminate\Http\Response
     */
    public function show(PenjualanDetail $penjualanDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PenjualanDetail  $penjualanDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(PenjualanDetail $penjualanDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PenjualanDetail  $penjualanDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $nama_input = "jumlah_" . $id;
        $detail = PenjualanDetail::find($id);
        $total_harga = $request[$nama_input] * $detail->harga_jual;

        $detail->jumlah = $request[$nama_input];
        $detail->sub_total = $total_harga - ($detail->diskon / 100 * $total_harga);
        $detail->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PenjualanDetail  $penjualanDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $detail = PenjualanDetail::find($id);
        $detail->delete();
    }

    public function newSession()
    {
        $penjualan = new Penjualan;
        $penjualan->kode_member = 0;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->kembali = 0;
        $penjualan->id_user = Auth::user()->id;
        $penjualan->save();

        session(['idpenjualan' => $penjualan->id_penjualan]);
        session(['id_user' => $penjualan->id_user]);

        return Redirect::route('transaksi.index');
    }


    public function saveData(Request $request)
    {
        $penjualan = Penjualan::find($request['idpenjualan']);
        $penjualan->kode_member = $request['member'];
        $penjualan->total_item = $request['totalitem'];
        $penjualan->total_harga = $request['total'];
        $penjualan->diskon = $request['diskon'];
        $penjualan->bayar = $request['bayar'];
        $penjualan->diterima = $request['diterima'];
        $penjualan->kembali = $request['kembali'];

        // dd($penjualan);
        $penjualan->update();

        // update stok data sesuai data yg dibeli
        $detail = PenjualanDetail::where('id_penjualan', '=', $request['idpenjualan'])->get();
        foreach ($detail as $d) {
            $produk = Produk::where('kode_produk', '=', $d->kode_produk)->first();
            $produk->stok -= $d->jumlah;
            $produk->update();
        }
        // return Redirect::route('transaksi.cetak');
        $setting = Setting::find(1);
        return view('penjualan_detail.selesai', compact('setting'));
    }


    public function loadForm($diskon, $total, $diterima)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;

        $data = array(
            "totalrp" => format_uang($total),
            "bayar" => $bayar,
            "bayarrp" => format_uang($bayar),
            "terbilang" => ucwords(terbilang($bayar)) . " Rupiah",
            "kembalirp" => $kembali,
            "kembaliterbilang" => ucwords(terbilang($kembali)) . " Rupiah"
        );
        return response()->json($data);
    }

    public function printNota()
    {
        $detail = PenjualanDetail::leftJoin('produk', 'produk.kode_produk', '=', 'penjualan_detail.kode_produk')->where('id_penjualan', '=', session('idpenjualan'))->get();

        $penjualan = Penjualan::find(session('idpenjualan'));
        $setting = Setting::find(1);

        $connector = new WindowsPrintConnector("POS-58");
        $printer = new Printer($connector);

        // membuat fungsi untuk membuat 1 baris tabel, agar dapat dipanggil berkali-kali dgn mudah
        function buatBaris4Kolom($kolom1, $kolom2, $kolom3, $kolom4)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 12;
            $lebar_kolom_2 = 4;
            $lebar_kolom_3 = 6;
            $lebar_kolom_4 = 6;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
            $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);
            $kolom4Array = explode("\n", $kolom4);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
                $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode($hasilBaris) . "\n";
        }

        // Membuat judul
        $printer->initialize();
        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT); // Setting teks menjadi lebih besar
        $printer->setJustification(Printer::JUSTIFY_CENTER); // Setting teks menjadi rata tengah
        $printer->text("Heymart\n");
        $printer->text("\n");


        // Data transaksi
        $printer->initialize();
        $dasar = ($penjualan['id_user']);
        $dasar2 = ($penjualan['total_harga']);
        $printer->text("Nama Kasir : $dasar \n");
        // $printer->text("Kasir : " . $dasar . "\n");
        // $printer->text("Waktu : " . $datajual->tgl_transaksi . "\n");
        // $printer->text("Waktu : " . $datajual['tgl_transaksi'] . "\n");
        $printer->text("Waktu :  $penjualan[created_at] \n");
        // $printer->text("Kode transaksi :  $detail[id_penjualan_detail] \n");

        // $printer->selectPrintMode();
        // Membuat tabel
        $printer->initialize(); // Reset bentuk/jenis teks
        $printer->text("--------------------------------\n");
        $printer->text(buatBaris4Kolom("Nama Produk", "Qty", "Harga", "Jumlah"));
        $printer->text("--------------------------------\n");
        foreach ($detail as $da => $kepo) {
            $penguranganDiskon = $kepo->harga_jual - $kepo->sub_total;
            $printer->text(buatBaris4Kolom(
                "$kepo->nama_produk",
                "$kepo->jumlah",
                number_format($kepo->harga_jual, 0, ',', '.'),
                number_format($kepo->sub_total, 0, ',', '.')
            ));

            $printer->text("\n");
            $printer->text(buatBaris4Kolom("Diskon", '', '', number_format($penguranganDiskon, 0, ',', '.')));
            $printer->text("--------------------------------\n");

            $printer->text(buatBaris4Kolom("Total", '', '', number_format($kepo->sub_total, 0, ',', '.')));
        }
        $printer->text(buatBaris4Kolom("Tunai", '', '', number_format($penjualan['diterima'], 0, ',', '.')));
        $printer->text(buatBaris4Kolom("Kembalian", '', '', number_format($penjualan['kembali'], 0, ',', '.')));


        $printer->text("\n");

        // Pesan penutup
        $printer->initialize();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Terima kasih telah berbelanja\n");
        $printer->selectPrintMode();
        $printer->text("Heymart\n");

        $printer->feed(3); // mencetak 5 baris kosong agar terangkat (pemotong kertas saya memiliki jarak 5 baris dari toner)
        $printer->cut();
        $printer->close();

        return redirect()->route('home');
    }

    public function notaPDF()
    {
        $detail = PenjualanDetail::leftJoin('produk', 'produk.kode_produk', '=', 'penjualan_detail.kode_produk')->where('id_penjualan', '=', session('idpenjualan'))->get();
        $penjualan = Penjualan::find(session('idpenjualan'));
        $setting = Setting::find(1);
        $no = 0;
        $pdf = PDF::loadView('penjualan_detail.notapdf', compact('detail', 'penjualan', 'setting', 'no'));
        $pdf->setPaper(array(0, 0, 609, 440), 'potrait');
        return $pdf->stream();
    }
}
