@extends('layouts.app')
@section('title')
Pengaturan
@endsection

@section('breadcrumb')
@parent
<li>Pengaturan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <form  class="form form-horizontal" method="post" data-toggle="validator" enctype="multipart/form-data">
            @csrf
            {{ method_field('PATCH') }}
<div class="box-body">
    <div class="alert alert-info alert-dismissable" style="display: none">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i class="icon fa fa-check"></i>
    Perubahan berhasil disimpan.
    </div>

<div class="form-group">
    <label for="nama" class="col-md-2 control-label"> Nama Perusahaan</label>
    <div class="col-md-6">
        <input type="text" id="nama" class="form-control" name="nama" required>
        <span class="help-block with-errors"></span>
    </div>
</div>

<div class="form-group">
    <label for="alamat" class="col-md-2 control-label">Alamat</label>
    <div class="col-md-10">
        <input type="text" id="alamat" class="form-control" name="alamat" required>
        <span class="help-block with-errors"></span>
    </div>
</div>

<div class="form-group">
    <label for="telepon" class="col-md-2 control-label">Telepon</label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="telepon" id="telepon" required>
        <span class="help-block with-errors"></span>
    </div>
</div>

<div class="form-group">
    <label for="logo" class="col-md-2 control-label">Logo Perusahaan</label>
    <div class="col-md-4">
        <input type="file" name="logo" class="form-control" id="logo">
        <br>
        <div class="tampil-logo"></div>
    </div>
</div>

<div class="form-group">
    <label for="kartu_member" class="col-md-2 control-label">Desain Kartu Member</label>
    <div class="col-md-4">
        <input type="file" name="kartu_member" class="form-control" id="kartu_member">
        <br>
        <div class="tampil-kartu"></div>
    </div>
</div>

<div class="form-group">
    <label for="diskon_member" class="col-md-2 control-label">Diskon Member %</label>
    <div class="col-md-2">
        <input type="number" class="form-control" name="diskon_member" id="diskon_member" required>
        <span class="help-block with-errors"></span>
    </div>
</div>

<div class="form-group">
    <label for="tipe_nota" class="col-md-2 control-label">Tipe Nota</label>
    <div class="col-md-2">
<select name="tipe_nota" id="tipe_nota" class="form-control">
    <option value="0">Nota Kecil</option>
    <option value="1">Nota Besar (PDF)</option>
</select>
    </div>
</div>

</div>



<div class="box-footer">
    <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Simpan Perubahan</button>
</div>
        </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$(function(){
    showData();
    $('.form').validator().on('submit', function(e){
        if(!e.isDefaultPrevented()){
            $.ajax({
                url: "setting/1",
                type: "POST",
                data: new FormData($(".form")[0]),
                async: false,
                processData: false,
                contentType: false,
                success: function(data){
                    showData();
                    $('.alert').css('display','block').delay(2000).fadeOut();
                },
                error: function(){
                    alert("Tidak dapat menyimpan data");
                }
            });
            return false
        }
    });
});

function showData(){
    $.ajax({
        url: "setting/1/edit",
        type: "GET",
        dataType: "JSON",
        success: function(data){
            $('#nama').val(data.nama_perusahaan);
            $('#alamat').val(data.alamat);
            $('#telepon').val(data.telepon);
            $('#diskon_member').val(data.diskon_member);
            $('#tipe_nota').val(data.tipe_nota);

d = new Date();
$('.tampil-logo').html('<img src="public/images/'+data.logo+'?'+d.getTime()+'" width="200">');
$('.tampil-kartu').html('<img src="public/images/'+data.kartu_member+'?'+d.getTime()+'" width="300">');
        },
        error: function(){
            alert("Tidak dapat menyimpan data");
        }
    });
}
</script>
@endsection