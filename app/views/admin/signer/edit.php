<div class="form-group">
    <label class="control-label">Nama</label><label style="color: red;">*</label>
    <input class="form-control" type="text" id="name" value="<?=$id->name;?>">
</div>
<div class="form-group">
    <label class="control-label">User </label><label style="color: red;">*</label>
    <select class="form-control" id="user_id">
        <option value="" disabled selected hidden>Pilih User</option>
        <?=$this->m_signer->select_user($data=$id->user_id);?>
    </select>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Email User </label><label style="color: red;">*</label>
            <input class="form-control" type="text" id="email_user" value="<?=$id->email_user;?>">
        </div>
        <div class="col-md-6">
            <label class="control-label">Email Digisign</label><label style="color: red;">*</label>
            <input class="form-control" type="text" id="email_digisign" value="<?=$id->email_digisign;?>">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Kuser Production </label><label style="color: red;">*</label>
            <input class="form-control" type="text" id="kuser_production" value="<?=$id->kuser_production;?>">
        </div>
        <div class="col-md-6">
            <label class="control-label">Kuser Sandbox</label><label style="color: red;">*</label>
            <input class="form-control" type="text" id="kuser_sandbox" value="<?=$id->kuser_sandbox;?>">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Token Production </label><label style="color: red;">*</label>
            <input class="form-control" type="text" id="token_production" value="<?=$id->token_production;?>">
        </div>
        <div class="col-md-6">
            <label class="control-label">Token Sandbox</label><label style="color: red;">*</label>
            <input class="form-control" type="text" id="token_sandbox" value="<?=$id->token_sandbox;?>">
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
            <select class="form-control" id="jenis_kelamin">
                <option value="Laki-laki" 
                <?php
                    if($id->jenis_kelamin=="Laki-laki"){
                        echo "Selected";
                    }
                ?>
                >Laki-laki
                </option>
                <option value="Perempuan"
                <?php
                    if($id->jenis_kelamin=="Perempuan"){
                        echo "Selected";
                    }
                ?>
                >Perempuan</option>
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Tempat Lahir </label>
            <input class="form-control" type="text" id="tempat_lahir" value="<?=$id->tempat_lahir;?>">
        </div>
        <div class="col-md-6">
            <label class="control-label">Tanggal Lahir </label>
            <input class="form-control tanggal" type="text" id="tgl_lahir" value="<?=$id->tgl_lahir;?>">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label class="control-label">Nomor KTP </label>
            <input class="form-control" type="number" id="id_ktp" value="<?=$id->id_ktp;?>">
        </div>
        <div class="col-md-6">
            <label class="control-label">Nomor NPWP </label>
            <input class="form-control" type="number" id="id_npwp" value="<?=$id->id_npwp;?>">
        </div>
    </div>
</div>
<div class="form-group">
    <label class="control-label">Alamat </label>
    <textarea rows="4" cols="50" class="form-control" id="alamat"><?=$id->alamat;?></textarea>
</div>
<div class="form-group">
    <label class="control-label">Provinsi </label>
    <select class="form-control" id="provincy">
        <option value="" disabled selected hidden>Pilih Provinsi</option>
        <?=$this->m_signer->select_provincy($data=$id->provinci);?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Kota/Kabupaten </label>
    <select class="form-control" id="kota">
        <?=$this->m_signer->select_kota($data=$id->kota);?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Kecamatan </label>
    <select class="form-control" id="kecamatan">
        <?=$this->m_signer->select_kecamatan($data=$id->kecamatan);?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Kelurahan </label>
    <select class="form-control" id="desa">
        <?=$this->m_signer->select_desa($data=$id->desa);?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Kode Pos </label>
    <input class="form-control" type="number" id="kode_pos" value="<?=$id->kode_pos;?>">
</div>
<input type="hidden" id="id" value="<?=$id->id;?>">