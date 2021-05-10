<div class="form-group">
    <label class="control-label">Jenis Tanda tangan </label>
    <div class="form-control"><?=$this->m_surat_keluar->label_jenis_ttd($id->jenis_ttd);?></div>
</div>
<div class="form-group">
    <label class="control-label">User </label>
    <input type="text" class="form-control" value="<?=$this->m_surat_keluar->get_user($id->user);?>" readonly>
</div>
<?php
if($id->jenis_ttd=='Digital'){
?>
    <div class="form-group">
        <label class="control-label">Penanda tangan </label>
        <input type="text" class="form-control" value="<?=$this->m_surat_keluar->get_signer($id->signer);?>" readonly/>
    </div>
<?php
}
?>
<div class="form-group">
    <label class="control-label">Nomor Surat </label>
    <input type="text" class="form-control" value="<?=$id->no_surat;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Perihal </label>
    <input type="text" class="form-control" value="<?=$id->perihal;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Type Surat </label>
    <input type="text" class="form-control" value="<?=$id->melalui;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Tanggal Terima </label>
    <input type="text" class="form-control" value="<?=$id->tgl_kirim;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Asal Surat</label>
    <input type="text" class="form-control" value="<?=$id->asal_surat;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Diusulkan </label>
    <input type="text" class="form-control" value="<?=$id->diusulkan;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Tujuan </label>
    <input type="text" class="form-control" value="<?=$id->tujuan;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Disetujui </label>
    <input type="text" class="form-control" value="<?=$this->m_surat_keluar->penerima_surat($id->disetujui);?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Jenis </label>
    <input type="text" class="form-control" value="<?=$id->jenis;?>" readonly>
</div>

<div class="form-group">
    <label class="control-label">Status </label>
    <div class="form-control"><?=$this->m_surat_keluar->label_status_keluar($id->status, $id->id_surat_keluar);?></div>
</div>
<div class="form-group">
    <label class="control-label">Document ID </label>
    <input type="text" class="form-control" value="<?=$id->document_id;?>" readonly>
</div>
<div class="form-group">
    <label class="control-label">Catatan </label>
    <input type="text" class="form-control" value="<?=$id->catatan;?>" readonly>
</div>