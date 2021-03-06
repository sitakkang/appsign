
$(document).ready(() => {
  var url_data=$('#attachment1').val();
  showPDF($('#attachment1').val());
  $('#pageNow').val(1);

  $(document).on('click','#lock1',function(e){
    $("#llx").val(e.pageX);
    $("#lly").val(e.pageY);
    var la = e.pageX-120;
    var llx_result = la-376;
    var lb = e.pageY + 36.68;
    var llb = lb-338;
    var lly_result = 841-llb;
    var urx = llx_result+95;
    var ury = lly_result+45;
    $("#llx_result").val(llx_result);
    $("#lly_result").val(lly_result); 
    $("#urx").val(urx);
    $("#ury").val(ury);
    if($('#lock1').val()=='lock1'){
      $('#unlock').removeClass('fa fa-unlock-alt fa-2x');
      $('#unlock').addClass('fa fa-lock fa-2x');
      $('#lock1').val('lock2');
      $('#unlock').attr('title','Unlock Signature');
      interact('.digital-signature').draggable(false);
    }else{
      interact('.digital-signature').draggable(true);
      $('#unlock').removeClass('fa fa-lock fa-2x');
      $('#unlock').addClass('fa fa-unlock-alt fa-2x');
      $('#lock1').val('lock1');
      $('#unlock').attr('title','Lock Signature');
    }
  });

  $(document).on('click','button#sign_btn',function(e){
    e.preventDefault();
    if($('#lock1').val()=='lock1'){
      notifNo('Please lock signature');
    }else{
      var pageNow = $('#pageNow').val();
      var llx = $('#llx_result').val();
      var lly = $('#lly_result').val();
      var urx = $('#urx').val();
      var ury = $('#ury').val();
      var type_surat = $('#type_surat').val();
      var url_act_sign_doc  = site_url+"surat_keluar/surat_keluar_admin/act_sign_doc";
      $.post(url_act_sign_doc,{
        pageNow:$("input[name*='pageNow_']").val(),
        llx:$("input[name*='llx_result_']").val(),
        lly:$("input[name*='lly_result_']").val(),
        urx:$("input[name*='urx_']").val(),
        ury:$("input[name*='ury_']").val(),
        id:$("input[name*='id_']").val(),
      })
      .done(function(result) {
        var obj = jQuery.parseJSON(result);
        if(obj.status == 1){
                  notifNo(obj.notif);
        }
        if(obj.status == 2){
                notifYesAuto(obj.notif);
                window.location.href = '../index';
        }
      })
      .fail(function(res) {
        alert("Error");
        console.log("Error", res.responseText);

      });
    }
    
  });
})

var _PDF_DOC,
    _CURRENT_PAGE,
    _TOTAL_PAGES,
    _PAGE_RENDERING_IN_PROGRESS = 0,
    _GOTO_PAGE,
    _CANVAS = document.querySelector('#pdf-canvas');

async function showPDF(pdf_url) {
    document.querySelector("#pdf-loader").style.display = 'block';
    try {
        _PDF_DOC = await pdfjsLib.getDocument({ url: pdf_url });
    }
    catch(error) {
        alert(error.message);
    }
    _TOTAL_PAGES = _PDF_DOC.numPages;
    document.querySelector("#pdf-loader").style.display = 'none';
    document.querySelector("#pdf-contents").style.display = 'block';
    document.querySelector("#pdf-total-pages").innerHTML = _TOTAL_PAGES;

    $( "#pdf-input" ).attr( "max", _TOTAL_PAGES );
    // $( "#pdf-input" ).val(_TOTAL_PAGES);

    showPage(1);
}

async function showPage(page_no) {
    _PAGE_RENDERING_IN_PROGRESS = 1;
    _CURRENT_PAGE = page_no;    
    document.querySelector("#pdf-next").disabled = true;
    document.querySelector("#pdf-prev").disabled = true;
    document.querySelector("#pdf-selectpage").disabled = true;
    // document.querySelector("#pdf-last").disabled = true;    
    document.querySelector("#pdf-canvas").style.display = 'none';
    document.querySelector("#page-loader").style.display = 'block';
    document.querySelector("#pdf-current-page").innerHTML = page_no;
    document.querySelector("#pdf-gotopage").disabled = true;
    try {
        var page = await _PDF_DOC.getPage(page_no);
    }
    catch(error) {
        alert(error.message);
    }
    var pdf_original_width = page.getViewport(1).width;
    var scale_required = _CANVAS.width / pdf_original_width;
    var viewport = page.getViewport(scale_required);
    _CANVAS.height = viewport.height;
    document.querySelector("#page-loader").style.height =  _CANVAS.height + 'px';
    document.querySelector("#page-loader").style.lineHeight = _CANVAS.height + 'px';
    var render_context = {
        canvasContext: _CANVAS.getContext('2d'),
        viewport: viewport
    };
    try {
        await page.render(render_context);
    }
    catch(error) {
        alert(error.message);
    }
    _PAGE_RENDERING_IN_PROGRESS = 0;
    document.querySelector("#pdf-next").disabled = false;
    document.querySelector("#pdf-prev").disabled = false;
    document.querySelector("#pdf-selectpage").disabled = false;
    document.querySelector("#pdf-canvas").style.display = 'block';
    document.querySelector("#page-loader").style.display = 'none';
    $( "#pdf-input" ).val(page_no);
}


document.querySelector("#pdf-prev").addEventListener('click', function() {
    if(_CURRENT_PAGE != 1){
        $('#unlock').removeClass("fa fa-unlock-alt fa-2x");
        $('#unlock').removeClass("fa fa-lock fa-2x");
        $('#unlock').addClass('fa fa-unlock-alt fa-2x');
        $('#unlock').attr('title','Lock Signature');
        $('#lock1').val('lock1');
        showPage(--_CURRENT_PAGE);
        $('#pageNow').val(_CURRENT_PAGE)
    }
});

document.querySelector("#pdf-selectpage").addEventListener('click', function() {
    _GOTO_PAGE=$('#pdf-input').val();
    if(_GOTO_PAGE <= _TOTAL_PAGES && _GOTO_PAGE>=1){
        $('#unlock').removeClass("fa fa-unlock-alt fa-2x");
        $('#unlock').removeClass("fa fa-lock fa-2x");
        $('#unlock').addClass('fa fa-unlock-alt fa-2x');
        $('#unlock').attr('title','Lock Signature');
        $('#lock1').val('lock1');
        showPage(parseInt(_GOTO_PAGE));
    }else{
      notifNo('Page'+ _GOTO_PAGE +' not exist.');
    }
});


document.querySelector("#pdf-next").addEventListener('click', function() {
    if(_CURRENT_PAGE != _TOTAL_PAGES){
        $('#unlock').removeClass("fa fa-unlock-alt fa-2x");
        $('#unlock').removeClass("fa fa-lock fa-2x");
        $('#unlock').addClass('fa fa-unlock-alt fa-2x');
        $('#unlock').attr('title','Lock Signature');
        $('#lock1').val('lock1');
        showPage(++_CURRENT_PAGE);
        $('#pageNow').val(_CURRENT_PAGE)
    }
});