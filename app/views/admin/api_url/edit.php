<div class="form-group">
    <label class="control-label">Api_key </label>
    <input class="form-control" type="text" name="api_key" value="<?=$id->api_key;?>">
</div>
<div class="form-group">
    <label class="control-label">URL </label>
    <input class="form-control" type="text" name="url" value="<?=$id->url;?>">
</div>
<div class="form-group">
    <label class="control-label">Type </label>
    <select class="form-control" name="type">
    	<option value="" disabled selected hidden>Pilih Type</option>
        <?=$this->m_api_url->select_type($data=$id->type);?>
    </select>
</div>
<input type="hidden" name="id" value="<?=$id->id;?>">