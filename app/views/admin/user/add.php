<div class="form-group">
    <label class="control-label">Nama Lengkap </label>
    <input class="form-control" type="text" id="fullname">
</div>
<div class="form-group">
    <label class="control-label">Username </label>
    <input class="form-control" type="text" id="username">
</div>
<div class="form-group">
    <label class="control-label">Password </label>
    <input class="form-control" type="password" id="password">
</div>
<div class="form-group">
    <label class="control-label">Ketik Ulang Password</label>
    <input class="form-control" type="password" id="passconf">
</div>
<div class="form-group">
    <label class="control-label">Level </label>
    <select class="form-control" id="level">
        <?php $this->m_admin->select_level($data=NULL); ?>
    </select>
</div>
<div class="form-group">
    <label class="control-label">Status</label>
    <select class="form-control" id="status">
        <option value="1">Aktif</option>
        <option value="2">Nonaktif</option>
    </select>
</div>