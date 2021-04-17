<div class="form-group">
    <label class="control-label">*Nama</label>
    <input class="form-control" type="text" id="name">
</div>
<div class="form-group">
    <label class="control-label">*Email User </label>
    <input class="form-control" type="text" id="email_user">
</div>
<div class="form-group">
    <label class="control-label">*Email Digisign</label>
    <input class="form-control" type="text" id="email_digisign">
</div>
<div class="form-group">
    <label class="control-label">*Kuser Production </label>
    <input class="form-control" type="text" id="kuser_production">
</div>
<div class="form-group">
    <label class="control-label">*Kuser Sandbox</label>
    <input class="form-control" type="text" id="kuser_sandbox">
</div>
<div class="form-group">
    <label class="control-label">No. KTP </label>
    <input class="form-control" type="number" id="id_ktp">
</div>
<div class="form-group">
    <label class="control-label">No. Npwp </label>
    <input class="form-control" type="number" id="id_npwp">
</div>
<div class="form-group">
    <label class="control-label">Jenis Kelamin </label>
    <select class="form-control" id="jenis_kelamin">
    	<option value="" disabled selected hidden>Pilih Jenis Kelamin</option>
    	<option value="Laki-laki">Laki-laki</option>
    	<option value="Perempuan">Perempuan</option>
    </select>
</div>
<div class="form-group">
    <label class="control-label">No. Telepon </label>
    <input class="form-control" type="number" id="telepon">
</div>
<div class="form-group">
    <label class="control-label">Alamat </label>
    <textarea rows="4" cols="50" class="form-control" id="alamat"></textarea>
</div>
<div class="form-group">
    <label class="control-label">Provinsi </label>
    <select class="form-control" id="provincy">
    	<option value="" disabled selected hidden>Pilih Provinsi</option>
        <?=$this->m_signer->select_provincy($data=NULL);?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Kota/Kabupaten </label>
    <select class="form-control" id="kota">
    	<option value="" disabled selected hidden>Pilih Kota/Kabupaten</option>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Kecamatan </label>
    <select class="form-control" id="kecamatan">
    	<option value="" disabled selected hidden>Pilih Kecamatan</option>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Kelurahan </label>
    <select class="form-control" id="desa">
    	<option value="" disabled selected hidden>Pilih Kelurahan</option>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Kode Pos </label>
    <input class="form-control" type="number" id="kode_pos">
</div>
<div class="form-group">
    <label class="control-label">Tempat Lahir </label>
    <input class="form-control" type="text" id="tempat_lahir">
</div>
<div class="form-group">
    <label class="control-label">Tanggal Lahir </label>
    <input class="form-control tanggal" type="text" id="tgl_lahir">
</div>
