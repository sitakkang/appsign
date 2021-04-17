<div class="form-group">
    <label class="control-label">Api Key</label>
    <input class="form-control" type="text" name="api_key">
</div>
<div class="form-group">
    <label class="control-label">URL </label>
    <input class="form-control" type="text" name="url">
</div>
<div class="form-group">
    <label class="control-label">Type </label>
    <select class="form-control" name="type">
    	<option value="" disabled selected hidden>Pilih Type</option>
        <?=$this->m_api_url->select_type($data=NULL);?>
    </select>
</div>