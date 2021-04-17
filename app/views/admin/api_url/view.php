<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card-header">
                    <?php if(!empty($panel)){echo $panel;}?>
                </div>
                <div class="card-body">
                    <button id="add_btn" class="btn btn-default"><i class="fa fa-plus"></i> Api URL</button><hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tbl_arsip">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>API Key</th>
                                    <th>URL</th>
                                    <th>Type</th>
                                    <th>Action</th>
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