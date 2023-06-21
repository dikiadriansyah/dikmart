@extends('layouts.app')
@section('title')
Daftar Pengeluaran
@endsection

@section('breadcrumb')
@parent
<li>Pengeluaran</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <a onclick="addForm()" class="btn btn-success"><i class="fa fa-plus-circle"></i>Tambah</a>
            </div>

<div class="box-body">
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Tanggal</th>
                <th>Jenis Pengeluaran</th>
                <th>Nominal</th>
                <th width="100">Aksi</th>

            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

        </div>
    </div>
</div>
@include('pengeluaran.form')
@include('pengeluaran.form2')

@endsection

@section('script')
<script type="text/javascript">
    var table, save_method;
    $(function(){
        table = $('.table').DataTable({
            "processing": true,
            "ajax":{
"url":"{{ route('pengeluaran.data') }}",
"type": "GET"
            }
        });

$('#modal-form form').validator().on('submit', function(e){
    if(!e.isDefaultPrevented()){
        var id = $('#id').val();
        if(save_method == "add")     url == "{{ route('pengeluaran.store') }}";
        else url == "pengeluaran/"+id;
        

     $.ajax({
     url: url,
     type: "POST",
     data: $('#modal-form form').serialize(),
     success: function(data){
        $('#modal-form').modal('hide');
        table.ajax.reload();
        console.log('berhasil');
     },
     error: function(){
        alert("Tidak Dapat Menyimpan Data");
     }
});
return false;

    }
});
    });

function addForm(){
    save_method = "add";
    $('input[name=_method]').val('POST');
    $('#modal-form').modal('show');
    $('#modal-form form')[0].reset();
    $('.modal-title').text('Tambah Pengeluaran');
}

function editForm(id){
    save_method = "edit";
    $('input[name=_method]').val('PATCH');
    $('#modal-form2 form')[0].reset();

$.ajax({
    url: "pengeluaran/"+id+"/edit",
    type: "GET",
    dataType: "JSON",
    success: function(data){
        $('#modal-form2').modal('show');
        $('.modal-title').text('Edit Pengeluaran2');
        $('#id2').val(data.id_pengeluaran);
        $('#jenis2').val(data.jenis_pengeluaran);
        $('#nominal2').val(data.nominal);

    },
    error: function(){
        alert("Tidak dapat menampilkan data");
    }
});

}

function deleteData(id){
    if(confirm("Apakah yakin data akan dihapus?")){
        $.ajax({
            url: "pengeluaran/"+id,
            type: "POST",
            data: {
                '_method': 'DELETE',
                '_token': $('meta[name=csrf-token]').attr('content')
            },
            success: function(data){
                table.ajax.reload();
            },
            error: function(){
                alert("Tidak dapat menghapus data");
            }
        }); 
    }
}


$('#update').click(function(e){
    e.preventDefault();

    // define variabel
    let id = $('#id2').val();
    let jenis2 = $('#jenis2').val();
    let nominal2 = $('#nominal2').val();  
    let token = $("meta[name='csrf-token']").attr("content");

    $.ajax({
        url: `pengeluaran/${id}`,
        type: "PUT",
        data: {
            "jenis2": jenis2,
            "nominal2": nominal2,
            "_token": token
        },
        success: function(response){
            console.log(response);

            $('#modal-form').modal('hide');
            window.location = 'pengeluaran';
        }
    })
})

</script>
@endsection
