<div class="form-group">
    <label class="control-label">Posisi </label>
    <select class="form-control" selectpicker name="posisi">
        	<?=$this->m_surat_keluar_admin->select_posisi($data_=NULL);?>
    </select>
    <input type="hidden" name="id_surat" value="<?=$id_surat?>">
</div>