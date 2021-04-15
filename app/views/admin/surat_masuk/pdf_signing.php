<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <?php if(!empty($panel)){echo $panel;}?>
                </div>
                <div class="card-body">
                    <button id="sign_btn" class="btn btn-default">Sign</button><hr>
                    <input type="hidden" name="type_surat_" id="type_surat" value="surat_masuk">
                    <input type="hidden" name="id_" id="id" value="<?php echo $id;?>">
                    <input type="hidden" id="llx" name="llx_">
                    <input type="hidden" id="lly" name="lly_">
                    <input type="hidden" id="llx_result" name="llx_result_">
                    <input type="hidden" id="lly_result" name="lly_result_">
                    <input type="hidden" id="urx" name="urx_">
                    <input type="hidden" id="ury" name="ury_">
                    <div class="content">
                    
                    <div class="document-toolbar-container-sticky">
                      <div class="document-toolbar">
                        <div class="d-flex justify-content-between">
                          <div class="d-block text-primary">
                            <strong>Page</strong> <span class="page-current" id="pageCurrent">1</span>/<span class="page-of" id="pageOf"></span>
                          </div><!-- .d-block -->
                        </div><!-- .justify-content-between -->
                      </div><!-- .document-toolbar -->
                    </div><!-- .document-toolbar -->

                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="document-container">
                            <div class="document-render" id="documentRender">
                              <div class="digital-signature" id="digitalSignature">
                                <img src="../src/img/signature.png" class="img-fluid signature-item">
                              </div><!-- .digital-signature -->
                            </div><!-- .document-render -->
                          </div><!-- .document-container -->
                        </div><!-- .col-## -->
                      </div><!-- .row -->
                    </div><!-- .container-fluid -->
                  </div><!-- .content -->
                </div><!-- .wrapper -->
                </div>
            </div>
        </div>
    </div>
</div>