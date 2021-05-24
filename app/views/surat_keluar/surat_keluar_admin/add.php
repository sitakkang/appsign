<div class="form-group">
    <label class="control-label">Jenis Tandatangan</label>
    <select class="form-control" name="jenis_ttd_" id="jenis_ttd">
        <?=$this->m_surat_keluar_admin->select_jenisttd($data=NULL);?>
    </select>
</div>
<div id="surat_signer" style="display: none;">
    <div class="form-group">
        <label class="control-label">Penanda Tangan </label>
        <select class="form-control" name="signer">
            <?=$this->m_surat_keluar_admin->select_approval($data=NULL,$id=$this->session->userdata('sess_id'));?>
        </select>
    </div>
</div>
<div id="surat_content" style="display: none;">
    <div class="form-group">
        <label class="control-label">Nomor Surat </label>
        <input class="form-control" type="text" name="no_surat">
    </div>
    <div class="form-group">
        <label class="control-label">Perihal </label>
        <textarea class="form-control" name="perihal" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label class="control-label">Type Surat</label>
        <select class="form-control" name="melalui">
            <?=$this->m_surat_keluar_admin->bentuk_surat($data=NULL);?>
        </select>
    </div>
    <div class="form-group">
        <label class="control-label">Jenis Surat </label>
        <select class="form-control" name="jenis">
            <?=$this->m_surat_keluar_admin->jenis_surat($data=NULL);?>
        </select>
    </div>
    <div class="form-group">
        <label class="control-label">Ditujukan ke </label>
        <textarea class="form-control" name="tujuan" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label class="control-label">Diusulkan oleh</label>
        <input class="form-control" type="text" name="diusulkan">
    </div>
    <div class="form-group">
        <label class="control-label">Tanggal Kirim </label>
        <input class="form-control tanggal" type="text" name="tgl_kirim" readonly>
    </div>
    <div class="form-group">
        <label class="control-label">Asal Surat </label>
        <select class="form-control" name="asal_surat">
            <?=$this->m_surat_keluar_admin->asal_surat($data=NULL);?>
        </select>
    </div>
    <div class="form-group">
        <label class="control-label">Catatan </label>
        <textarea class="form-control" name="catatan" rows="5"></textarea>
    </div>
</div>