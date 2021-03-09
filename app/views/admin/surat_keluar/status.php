<div class="form-group">
    <label class="control-label">Status </label>
    <select class="form-control" name="status">
        <?=$this->m_surat_masuk->select_status($id->status);?>
    </select>
    <input type="hidden" name="id_surat" value="<?=$id->id_surat_keluar;?>">
</div>