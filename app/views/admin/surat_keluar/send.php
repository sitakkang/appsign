<div class="form-group">
    <label class="control-label">Disetujui oleh </label>
    <select class="form-control" id="disetujui">
        <?=$this->m_surat_masuk->select_tujuan($tujuan);?>
    </select>
    <input type="hidden" name="id_surat" value="<?=$id_surat?>">
</div>