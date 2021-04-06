<div class="form-group">
    <label class="control-label">Sent to </label>
    <select class="form-control" selectpicker id="vendor">
        	<?=$this->m_surat_masuk->select_vendor($data_=NULL);?>
    </select>
    <input type="hidden" name="id_surat" value="<?=$id_surat?>">
</div>