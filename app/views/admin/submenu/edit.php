<div class="form-group">
    <label class="control-label">Main Menu </label>
    <select class="form-control multi-select" id="id_menu">
        <?php $this->m_admin->list_menu($id->id_menu, $akses); ?>
    </select>
</div>
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
<div class="form-group">
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
<input type="hidden" id="id_sub" value="<?=$id->id_submenu;?>">
<input type="hidden" id="name_old" value="<?=$id->name;?>">