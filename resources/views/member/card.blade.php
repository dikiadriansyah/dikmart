<html>
    <head>
        <title>Cetak Kartu Member</title>

        <style>
            .box{
                position: relative;
            }
            .card{
                width: 501.732pt;
                height: 147.402pt;
            }
            .kode{
                position: absolute;
                top: 110pt;
                left: 10pt;
                color: #fff;
                font-size: 15pt;
            }
            .barcode{
                position: absolute;
                top: 15pt;
                left: 280pt;
                font-size: 10pt;
            }
        </style>
    </head>
    <body>
      <table width="100%">
    @foreach($datamember as $dm)
    <tr>
        <td align="center">
            <div class="box">
                <img src="{{ asset('public/images/Tulips.jpg') }}" class="card" alt="">
            <div class="kode">{{ $dm->kode_member }}</div>
            <div class="barcode">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($dm->kode_member,'C39') }}" height="30" width="130" alt="">
            <br>
            {{ $dm->kode_member }}
            </div>
            </div>
        </td>
    </tr>
    @endforeach    
    </table>  
    </body>
</html>