<div class="form-group">
    <label class="control-label">Nomor Surat </label>
    <input class="form-control" type="text" name="no_surat">
</div>
<div class="form-group">
    <label class="control-label">Perihal </label>
    <textarea class="form-control" name="perihal" rows="3"></textarea>
</div>
<div class="form-group">
    <label class="control-label">Jenis Surat </label>
    <select class="form-control" name="jenis">
        <?=$this->m_surat_masuk->jenis_surat($data=NULL);?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Pengirim </label>
    <textarea class="form-control" name="pengirim" rows="3"></textarea>
</div>
<div class="form-group">
    <label class="control-label">Penerima </label>
    <input class="form-control" type="text" name="penerima">
</div>
<div class="form-group">
    <label class="control-label">Tanggal Terima </label>
    <input class="form-control tanggal" type="text" name="tgl_terima" readonly>
</div>
<div class="form-group">
    <label class="control-label">Bentuk </label>
    <select class="form-control" name="melalui">
        <?=$this->m_surat_masuk->bentuk_surat($data=NULL);?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Catatan </label>
    <textarea class="form-control" name="catatan" rows="5"></textarea>
</div>