<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <?php if(!empty($panel)){echo $panel;}?>
                </div>
                <div class="card-body">
                <form class="form-inline">
                    <div class="form-group">
                        <select class="form-control" id="cat_menu" title="Kategori Menu">
                        <?php echo $this->m_admin->list_cat_menu(); ?>
                        </select>
                    </div>&nbsp;&nbsp;
                    <button id="add_btn" class="btn btn-default"><i class="fa fa-plus-circle"></i> Tambah Data</button>
                    &nbsp;&nbsp;<a href="<?=current_url();?>" class="btn btn-default"><i class="fa fa-refresh"></i> Refresh</a>
                </form><hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tabel_custom">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Icon</th>
                                <th>Link Icon2</th>
                                <th>Name</th>
                                <th>Link</th>
                                <th>Sub</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="popup_menu" class="popup_box">
    <button class="btn btn-default btn-block" id="edit_btn"><i class="ion-edit"></i>&nbsp;&nbsp;Ubah</button>
    <button class="btn btn-default btn-block" id="delete_btn"><i class="ion-trash-a"></i>&nbsp;&nbsp;Hapus</button>
</div>