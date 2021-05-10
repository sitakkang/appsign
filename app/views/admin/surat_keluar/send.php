<div class="form-group">
    <label class="control-label">Kirim Ke </label>
    <select class="form-control" id="disetujui" disabled>
        <?=$this->m_surat_keluar->select_approval_admin($data_=$tujuan);?>
    </select>
    <input type="hidden" name="id_surat" value="<?=$id_surat?>">
</div>