<html>
    <head>
        <title>Nota PDF</title>
        <style type="text/css">
        table td{
            font: arial 12px;
        }
        table.data td, table.data th{
            border: 1px solid #ccc;
            padding: 5px;

        }

        table.data th{
            text-align: center;
        }
        table.data{
            border-collapse: collapse;
        }
        </style>
    </head>
    <body>
        <table width="100%">
            <tr>
                <td rowspan="3" width="60%">
                    <img src="{{ asset('public/images/' . $setting->logo) }}" width="150" alt="">
                    <br>
                    {{ $setting->alamat }}
                    <br>
                </td>
                <td>Kode Member</td>
                <td>: {{ $penjualan->kode_member }}</td>
            </tr>
        </table>

        <table width="100%" class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Diskon</th>
                    <th>Subtotal</th>

                </tr>
                <tbody>
                    @foreach($detail as $d)
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td align="right">
                            {{ format_uang($d->harga_jual) }}
                        </td>
                        <td>{{ $d->jumlah }}</td>
                        <td align="right">{{ 
                         format_uang($d->diskon) 
                         }} %</td>
                        {{-- $total - ($diskon / 100 * $total) --}}
                        <td align="right">{{ format_uang($d->sub_total) }}</td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="6" align="right"><b>Total Harga</b></td>
                        <td align="right"><b>{{ format_uang($penjualan->total_harga) }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><b>Diskon</b></td>
                        <td align="right"><b>{{ 
                       format_uang($penjualan->total_harga * $penjualan->diskon / 100)
                         }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><b>Total Bayar</b></td>
                        <td align="right"><b>{{ format_uang($penjualan->bayar) }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><b>Diterima</b></td>
                        <td align="right"><b>{{ format_uang($penjualan->diterima) }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="6" align="right"><b>Kembali</b></td>
                        <td align="right"><b>{{ format_uang($penjualan->diterima - $penjualan->bayar) }}</b></td>
                    </tr>
                </tfoot>

            </thead>
        </table>

        <table width="100%">
<tr>
    <td>
        <b>Terimakasih telah berbelanja dan sampai jumpa</b>
    </td>
    <td align="center">
Kasir 
<br>
<br>
<br>
{{ Auth::user()->name }}
    </td>
</tr>
        </table>

    </body>
</html>