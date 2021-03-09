<div class="form-group">
    <label class="control-label">Ditujukan ke </label>
    <select class="form-control" selectpicker id="ditujukan">
        <?php
        	echo $this->m_surat_masuk->select_tujuan($tujuan);
        ?>
    </select>
    <input type="hidden" name="id_surat" value="<?=$id_surat?>">
</div>