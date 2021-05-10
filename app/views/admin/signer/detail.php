<div class="form-group">
    <label class="control-label">Nama</label>
    <input type="text" class="form-control" value="<?=$id->name;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">User </label>
            <input type="text" class="form-control" value="<?=$this->m_signer->get_userdata($id->user_id);?>" readonly>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">UserID Digisign Production</label>
            <input type="text" class="form-control" value="<?=$id->digisign_user_id_production;?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="control-label">UserID Digisign Sandbox</label>
            <input type="text" class="form-control" value="<?=$id->digisign_user_id_sandbox;?>" readonly>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Email User </label>
            <input type="text" class="form-control" value="<?=$id->email_user;?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="control-label">Email Digisign</label>
            <input type="text" class="form-control" value="<?=$id->email_digisign;?>" readonly>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Kuser Production </label>
            <input type="text" class="form-control" value="<?=$id->kuser_production;?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="control-label">Kuser Sandbox</label>
            <input type="text" class="form-control" value="<?=$id->kuser_sandbox;?>" readonly>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Token Production </label>
            <input type="text" class="form-control" value="<?=$id->token_production;?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="control-label">Token Sandbox</label>
            <input type="text" class="form-control" value="<?=$id->token_sandbox;?>" readonly>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">No. Telepon </label>
            <input type="text" class="form-control" value="<?=$id->telepon;?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="control-label">Jenis Kelamin </label>
            <input type="text" class="form-control" value="<?=$id->jenis_kelamin;?>" readonly>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Tempat Lahir </label>
            <input type="text" class="form-control" value="<?=$id->tempat_lahir;?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="control-label">Tanggal Lahir </label>
            <input type="text" class="form-control" value="<?=$id->tgl_lahir;?>" readonly>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Nomor KTP </label>
            <input type="text" class="form-control" value="<?=$id->id_ktp;?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="control-label">Nomor NPWP </label>
            <input type="text" class="form-control" value="<?=$id->id_npwp;?>" readonly>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label">Alamat </label>
    <input type="text" class="form-control" value="<?=$id->alamat;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Provinsi </label>
    <input type="text" class="form-control" value="<?=$this->m_signer->get_provincy($id->provinci);?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Kota/Kabupaten </label>
    <input type="text" class="form-control" value="<?=$this->m_signer->get_kota($id->kota);?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Kecamatan </label>
    <input type="text" class="form-control" value="<?=$this->m_signer->get_kecamatan($id->kecamatan);?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Kelurahan </label>
    <input type="text" class="form-control" value="<?=$this->m_signer->get_desa($id->desa);?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Kode Pos </label>
    <input type="text" class="form-control" value="<?=$id->kode_pos;?>" readonly>
</div>