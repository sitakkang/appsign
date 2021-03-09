<div class="form-group">
    <label class="control-label">Main Menu </label>
    <select class="form-control multi-select" id="id_menu">
        <?php $this->m_admin->list_menu($data=NULL, $akses); ?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Position </label>
    <input class="form-control" type="number" id="position">
</div>
<div class="form-group">
    <label class="control-label">Level</label>
    <select class="form-control multi-select" id="level" multiple>
        <?php $this->m_admin->select_level($data=NULL); ?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Icon </label>
    <div  id="target"></div>
    <input type="hidden" id="icon"> 
</div>
<div class="form-group">
    <label class="control-label">Link Icon2 </label>
    <input class="form-control" type="text" id="icon2">
</div>
<div class="form-group">
    <label class="control-label">Name </label>
    <input class="form-control" type="text" id="name">
</div>
<div class="form-group">
    <label class="control-label">Link </label>
    <input class="form-control" type="text" id="link" value="#" placeholder="#">
</div>
<div class="form-group">
    <label class="control-label">Status</label>
    <select class="form-control" id="status">
        <option value="1">Aktif</option>
        <option value="2">Nonaktif</option>
    </select>
</div>