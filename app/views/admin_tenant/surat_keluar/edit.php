<div class="form-group">
    <label class="control-label">Nomor Surat </label>
    <input class="form-control" type="text" name="no_surat" value="<?=$id->no_surat;?>">
</div>
<div class="form-group">
    <label class="control-label">Perihal </label>
    <textarea class="form-control" name="perihal" rows="3"><?=$id->perihal;?></textarea>
</div>
<div class="form-group">
    <label class="control-label">Jenis Surat </label>
    <select class="form-control" name="jenis">
        <?=$this->m_surat_keluar->jenis_surat($id->jenis);?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Ditujukan ke </label>
    <textarea class="form-control" name="tujuan" rows="3"><?=$id->tujuan;?></textarea>
</div>
<div class="form-group">
    <label class="control-label">Diusulkan Oleh </label>
    <input class="form-control" type="text" name="diusulkan" value="<?=$id->diusulkan;?>">
</div>
<div class="form-group">
    <label class="control-label">Tanggal Kirim </label>
    <input class="form-control tanggal" type="text" name="tgl_kirim" value="<?=$id->tgl_kirim;?>">
</div>
<div class="form-group">
    <label class="control-label">Bentuk </label>
    <select class="form-control" name="melalui">
        <?=$this->m_surat_keluar->bentuk_surat($id->melalui);?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Catatan </label>
    <textarea class="form-control" name="catatan" rows="5"><?=$id->catatan;?></textarea>
    <input type="hidden" name="id_surat" value="<?=$id->id_surat_keluar;?>">
</div>