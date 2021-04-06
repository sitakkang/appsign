<div class="form-group">
    <label class="control-label">Process Signature </label>
    <select class="form-control" selectpicker id="ditujukan">
        	<?=$this->m_surat_masuk->select_approval($data_=NULL);?>
    </select>
    <input type="hidden" name="id_surat" value="<?=$id_surat?>">
</div>