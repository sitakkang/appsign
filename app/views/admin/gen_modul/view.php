<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-syringe"></i> &nbsp;<b>Generate Main Modul</b>
                </div>
                <div class="card-body">

                    <div class="row form">
                        <div class="form-group">
                            <div class="col">
                                <input class="form-control" type="text" id="folder_name1" placeholder="Nama Folder">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col">
                                <input class="form-control" type="text" id="file_name1" placeholder="Nama File">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col">
                                <button class="btn btn-default" id="create_main_btn">Buat</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-syringe"></i> &nbsp;<b>Generate Sub Modul</b>
                    </div>
                    <div class="card-body">
    
                        <div class="row">
                            <div class="form-group">
                                <div class="col">
                                    <select class="form-control" id="folder_name2">
                                        <?php foreach($dropdown_dir as $key => $val){ ?>
                                        <option value="<?=$val;?>"><?=$val;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <input class="form-control" type="text" id="file_name2" placeholder="Nama File">
                                </div>
                            </div><div class="form-group">
                                <div class="col checkbox">
                                    <label>
                                        <input type="checkbox" value="1" id="check_ctrl" checked>
                                        Controllers Folder
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col checkbox">
                                    <label>
                                        <input type="checkbox" value="1" id="check_mdl" checked>
                                        Models Folder
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col checkbox">
                                    <label>
                                        <input type="checkbox" value="1" id="check_lib" checked>
                                        Library Folder
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col checkbox">
                                    <label>
                                        <input type="checkbox" value="1" id="check_view" checked>
                                        Views Folder
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col checkbox">
                                    <label>
                                        <input type="checkbox" value="1" id="check_js" checked>
                                        Javascript Folder
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <button class="btn btn-default" id="create_sub_btn">Buat</button>
                                </div>
                            </div>
                        </div>
    
                    </div>
                </div>
            </div>
    </div>
</div>