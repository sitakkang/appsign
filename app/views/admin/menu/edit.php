<div class="form-group">
    <label class="control-label">Position </label>
    <input class="form-control" type="number" id="position" value="<?=$id->position;?>">
</div>
<div class="form-group">
    <label class="control-label">Level</label>
    <select class="form-control multi-select" id="level" multiple>
        <?php $this->m_admin->select_level($id->level); ?>
    </select>
</div>
<div class="form-group"> 
    <label class="control-label">Icon </label>
    <div  id="target"></div>
    <input type="hidden" id="icon" value="<?php echo $id->icon ?>"> 
</div>
<div class="form-group">
    <label class="control-label">Link Icon2 </label>
    <input class="form-control" type="text" id="icon2" value="<?=$id->icon2;?>">
</div>
<div class="form-group">
    <label class="control-label">Name </label>
    <input class="form-control" type="text" id="name" value="<?=$id->name;?>">
</div>
<input type="hidden" value="<?=$akses;?>" id="akses">
<div class="form-group">
    <label class="control-label">Sub Menu </label>
    <select class="form-control" id="sub">
        <option value="1"<?php if($id->sub == 1){echo ' selected="selected"';};?>>No</option>
        <option value="2"<?php if($id->sub == 2){echo ' selected="selected"';};?>>Yes</option>
    </select>
</div>
<div class="form-group" id="link_display">
    <label class="control-label">Link </label>
    <input class="form-control" type="text" id="link" value="<?=$id->link;?>">
</div>
<div class="form-group">
    <label class="control-label">Status</label>
    <select class="form-control" id="status">
        <option value="1"<?php if($id->status == 1){echo ' selected="selected"';};?>>Aktif</option>
        <option value="2"<?php if($id->status == 2){echo ' selected="selected"';};?>>Nonaktif</option>
    </select>
</div>
<input type="hidden" id="id_menu" value="<?=$id->id_menu;?>">
<input type="hidden" id="name_old" value="<?=$id->name;?>">