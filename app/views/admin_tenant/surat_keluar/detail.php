<div class="form-group">
    <label class="control-label">Nomor Surat </label>
    <div class="form-control"><?php echo $id->no_surat;?></div>
</div>
<div class="form-group">
    <label class="control-label">Tanggal Terima </label>
    <div class="form-control"><?php echo $id->tgl_kirim;?></div>
</div>
<div class="form-group">
    <label class="control-label">Diusulkan </label>
    <div class="form-control"><?php echo $id->diusulkan;?></div>
</div>
<div class="form-group">
    <label class="control-label">Tujuan </label>
    <div class="form-control"><?php echo $id->tujuan;?></div>
</div>
<div class="form-group">
    <label class="control-label">Disetujui </label>
    <div class="form-control"><?php echo $this->m_surat_keluar->penerima_surat($id->disetujui);?></div>
</div>
<div class="form-group">
    <label class="control-label">Jenis </label>
    <div class="form-control"><?php echo $id->jenis;?></div>
</div>
<div class="form-group">
    <label class="control-label">Perihal </label>
    <div class="form-control"><?php echo $id->perihal;?></div>
</div>
<div class="form-group">
    <label class="control-label">Status </label>
    <div class="form-control"><?php echo $this->m_surat_keluar->label_status_keluar($id->status, $id->id_surat_keluar);?></div>
</div>
<div class="form-group">
    <label class="control-label">Bentuk </label>
    <div class="form-control"><?php echo $id->melalui;?></div>
</div>
<div class="form-group">
    <label class="control-label">Document ID </label>
    <div class="form-control"><?php echo $id->document_id;?></div>
</div>
<div class="form-group">
    <label class="control-label">Catatan </label>
    <div class="form-control"><?php echo $id->catatan;?></div>
</div>