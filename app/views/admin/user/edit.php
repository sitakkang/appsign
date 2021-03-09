<div class="form-group">
    <label class="control-label">Fullname </label>
    <input class="form-control" type="text" id="fullname" value="<?=$id->fullname;?>">
</div>
<div class="form-group">
    <label class="control-label">Username </label>
    <input class="form-control" type="text" id="username" value="<?=$id->username;?>">
</div>
<div class="form-group">
    <label class="control-label">Level </label>
    <select class="form-control" id="level">
        <?php $this->m_admin->select_level($id->level); ?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Status</label>
    <select class="form-control" id="status">
        <option value="1"<?php if($id->status == 1){echo ' selected="selected"';}?>>Aktif</option>
        <option value="2"<?php if($id->status == 2){echo ' selected="selected"';}?>>Nonaktif</option>
    </select>
</div>
<input type="hidden" id="id_user" value="<?=$id->id_user;?>">
<input type="hidden" id="username_old" value="<?=$id->username;?>">