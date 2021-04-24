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

                    <div class="content">
                      <div class="wrapper">
                        <div class="content">
                          <div class="content-header">
                            <div class="d-flex justify-content-between">
                              <div id="pdf-buttons">
                                  <input type="number" min="1" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="pdf-input" name="input_page_">
                                  <button id="pdf-gotopage" value="1">Page</button>
                                  <button id="pdf-first">&lsaquo;&lsaquo;First</button>
                                  <button id="pdf-prev">&lsaquo;Prev</button>
                                  <button id="pdf-next">Next&rsaquo;</button>  
                                  <button id="pdf-last">Last&rsaquo;&rsaquo;</button> 
                              </div>
                              <div id="page-count-container">Page <div id="pdf-current-page"></div> of <div id="pdf-total-pages"></div></div>        
                            </div><!-- .document-toolbar -->
                          </div><!-- .content-header -->
                          <div class="document-toolbar-container-sticky">
                            
                          </div><!-- .document-toolbar -->
                        </div>
                      </div>
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="document-container">
                              <div id="pdf-main-container">
                                <div class="document-render" id="documentRender">
                                  <div id="pdf-loader">Loading document ...</div>
                                  <div id="pdf-contents">
                                      <div class="digital-signature" id="digitalSignature">
                                        <img src="../src/img/signature.png" class="img-fluid signature-item">
                                      </div>
                                      <div id="pdf-meta">
                                      </div>
                                      <canvas id="pdf-canvas" width="595">

                                      </canvas>
                                      <div id="page-loader">Loading page ...</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <input type="hidden" name="pageNow_" id="pageNow">
                      <input type="hidden" name="type_surat_" id="type_surat" value="surat_keluar">
                      <input type="hidden" name="attachment1_" id="attachment1" value="<?php echo $attachment;?>">
                      <input type="hidden" name="id_" id="id" value="<?php echo $id;?>">
                      <input type="hidden" id="llx" name="llx_">
                      <input type="hidden" id="lly" name="lly_">
                      <input type="hidden" id="llx_result" name="llx_result_">
                      <input type="hidden" id="lly_result" name="lly_result_">
                      <input type="hidden" id="urx" name="urx_">
                      <input type="hidden" id="ury" name="ury_">
                    </div><!-- .content -->
                </div>
            </div>
        </div>
    </div>
</div>