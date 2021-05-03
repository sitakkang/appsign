<div class="form-group">
    <label class="control-label">Nama</label>
    <div class="form-control"><?=$id->name;?></div>
</div>
<div class="form-group">
    <label class="control-label">User</label>
    <div class="form-control"><?php echo $this->m_signer->get_userdata($id->user_id);?></div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Email User </label>
            <div class="form-control"><?=$id->email_user;?></div>
        </div>
        <div class="col-md-6">
            <label class="control-label">Email Digisign</label>
            <div class="form-control"><?=$id->email_digisign;?></div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Kuser Production </label>
            <div class="form-control"><?=$id->kuser_production;?></div>
        </div>
        <div class="col-md-6">
            <label class="control-label">Kuser Sandbox</label>
            <div class="form-control"><?=$id->kuser_sandbox;?></div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Token Production </label>
            <div class="form-control"><?=$id->token_production;?></div>
        </div>
        <div class="col-md-6">
            <label class="control-label">Token Sandbox</label>
            <div class="form-control"><?=$id->token_sandbox;?></div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">No. Telepon </label>
            <input class="form-control" type="number" id="telepon" value="<?=$id->telepon;?>">
        </div>
        <div class="col-md-6">
            <label class="control-label">Jenis Kelamin </label>
            <div class="form-control" id="jenis_kelamin"><?=$id->jenis_kelamin;?></div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Tempat Lahir </label>
            <div class="form-control"><?=$id->tempat_lahir;?></div>
        </div>
        <div class="col-md-6">
            <label class="control-label">Tanggal Lahir </label>
            <div class="form-control"><?=$id->tgl_lahir;?></div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Nomor KTP </label>
            <div class="form-control"><?=$id->id_ktp;?></div>
        </div>
        <div class="col-md-6">
            <label class="control-label">Nomor NPWP </label>
            <div class="form-control"><?=$id->id_npwp;?></div>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label">Alamat </label>
    <div class="form-control"><?=$id->alamat;?></div>
</div>
<div class="form-group">
    <label class="control-label">Provinsi </label>
    <div class="form-control"><?php echo $this->m_signer->get_provincy($id->provinci);?></div>
</div>
<div class="form-group">
    <label class="control-label">Kota/Kabupaten </label>
    <div class="form-control"><?php echo $this->m_signer->get_kota($id->kota);?></div>
</div>
<div class="form-group">
    <label class="control-label">Kecamatan </label>
    <div class="form-control"><?php echo $this->m_signer->get_kecamatan($id->kecamatan);?></div>
</div>
<div class="form-group">
    <label class="control-label">Kelurahan </label>
    <div class="form-control"><?php echo $this->m_signer->get_desa($id->desa);?></div>
</div>
<div class="form-group">
    <label class="control-label">Kode Pos </label>
    <div class="form-control"><?=$id->kode_pos;?></div>
</div>