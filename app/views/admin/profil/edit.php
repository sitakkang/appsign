<?php if(empty($id->avatar)){$link = base_url('img/noimage.png');}else{$link = base_url($id->avatar);} ?>
<div class="text-center">
    <img class="card-img-top" src="<?=$link;?>">
</div>
<hr>
<div class="row button-edit">
    <div class="col-md-6">
        <button class="btn btn-default btn-block" type="button" id="edit_ava">Avatar </button>
    </div>
    <div class="col-md-6">
        <button class="btn btn-default btn-block" type="button" id="edit_pass">Password </button>
    </div>
</div>
<hr>
<div class="form-group">
    <label class="control-label">Nama Lengkap </label>
    <input class="form-control" type="text" id="fullname" value="<?=$id->fullname;?>">
</div>
<div class="form-group">
    <label class="control-label">Username </label>
    <input class="form-control" type="text" id="username" value="<?=$id->username;?>">
</div><hr>
<input type="hidden" id="username_old" value="<?=$id->username;?>">
<input type="hidden" id="id_user" value="<?=$id->id_user;?>">
<button class="btn btn-default btn-block" id="save_edit_btn">Simpan </button>