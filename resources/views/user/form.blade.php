<div class="modal" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form class="form-horizontal" data-toggle="validator" method="post">
            @csrf 
            {{ method_field('POST') }}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    <h3 class="modal-title"></h3>
</div>

<div class="modal-body">
    <input type="hidden" id="id" name="id">
    <div class="form-group">
        <label for="nama" class="col-md-3 control-label">Nama User</label>
        <div class="col-md-6">
            <input type="text" id="nama" name="nama" class="form-control" autofocus required>
        <span class="help-block with-errors"></span>
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-md-3 control-label">Email</label>
        <div class="col-md-6">
            <input type="email" id="email" class="form-control" name="email" required>
            <span class="help-block with-errors"></span>
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-md-3 control-label">Password</label>
        <div class="col-md-6">
            <input type="password" id="password" class="form-control" name="password" required>
     <span class="help-block with-errors"></span>
        </div>
    </div>

    <div class="form-group">
        <label for="password1" class="col-md-3 control-label">Ulang Password</label>
        <div class="col-md-6">
            <input type="password" name="password1" id="password1" class="form-control" data-match="#password" required>
       <span class="help-block with-errors"></span>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary btn-save"><i class="fa fa-floppy-o"></i> Simpan</button>
    <button type="button" class="btn btn-warning"><i class="fa fa-arrow-circle-left"></i> Batal</button>

</div>

        </form>
    </div>
</div>
</div>