<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header">
                    <?php if(!empty($panel)){echo $panel;}?>
                </div>
                <div class="card-body">
                    

                    <div class="content">
                        <div align="left">
                          <button id="sign_btn" class="btn btn-primary">
                            <span class="fas fa-signature text-light"></span> Sign Document
                          </button>
                        </div>
                          <div align="center">
                              <div class="btn-group grp" role="group" aria-label="Page">
                                <div id="pdf-buttons">
                                  <div class="row" align="center">
                                    <button id="pdf-prev" class="btn btn-light mt-2 mb-2" title="Prev"><span class="fa fa-chevron-circle-left text-dark fa-lg"></span></button>
                                    <button id="pdf-gotopage" class="btn btn-light mt-2 mb-2" title="GoTo Page">
                                      Page <div id="pdf-current-page"></div> of <div id="pdf-total-pages"></div>
                                    </button>
                                    <button id="pdf-next" class="btn btn-light mt-2 mb-2" title="Next">
                                      <span class="fa fa-chevron-circle-right text-dark fa-lg"></span>
                                    </button>

                                    <input type="number" min="1" class="mt-2 mb-2" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="pdf-input" name="input_page_" value="1">
                                    <button id="pdf-selectpage" value="1" class="btn btn-light mt-2 mb-2" title="Goto Page"><span class="fas fa-angle-double-right text-dark fa-lg"></button>
                                  </div>
                                
                                </div>
                              </div>        
                          </div><!-- .document-toolbar -->
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
                                        <button id="lock1" class="btn btn-warning lock active" style="" value="lock1">
                                          <span id="unlock" class="fa fa-unlock-alt fa-2x" aria-hidden="true" title="Lock Signature"></span>
                                        </button>
                                        <img src="../../../img/signature/signature.png" class="img-fluid signature-item">
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