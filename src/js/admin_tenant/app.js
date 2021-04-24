// $(document).ready(() => {
//   'use strict';
//   var url_ctrl = site_url+"admin_tenant/surat_masuk/";
//   var documentToolbar = $('.document-toolbar')
//   var contentHeader = $('.content-header')
//   var distance = contentHeader.outerHeight()
//   $('.document-toolbar-container-sticky').css('height', documentToolbar.outerHeight())
  
//   $(window).scroll(() => {
//     if ($(window).scrollTop() >= distance) {
//       documentToolbar.addClass('document-toolbar-sticky')
//     } else {
//       documentToolbar.removeClass('document-toolbar-sticky')
//     }
//     var scrollDistance = $(window).scrollTop()
//     var result = Math.abs(Math.round(scrollDistance / 850));
//     if (result == 0) {
//       result = 1
//     }
//     $('#pageCurrent').text(result)
//   })
//   if ($(window).scrollTop() > distance) {
//     documentToolbar.addClass('document-toolbar-sticky')
//   }

//   $(document).on('click','button#sign_btn',function(e){
//     e.preventDefault();
//     var pageNow = $('#pageNow').val();
//     var llx = $('#llx_result').val();
//     var lly = $('#lly_result').val();
//     var urx = $('#urx').val();
//     var ury = $('#ury').val();
//     var type_surat = $('#type_surat').val();
//     var url_act_sign_doc  = site_url+"admin_tenant/"+type_surat+"/act_sign_doc";
//     $.post(url_act_sign_doc,{
//       llx:$("input[name*='llx_result_']").val(),
//       lly:$("input[name*='lly_result_']").val(),
//       urx:$("input[name*='urx_']").val(),
//       ury:$("input[name*='ury_']").val(),
//       id:$("input[name*='id_']").val(),
//       pageNow:$("input[name*='pageNow_']").val(),
//     })
//     .done(function(result) {
//       var obj = jQuery.parseJSON(result);
//       if(obj.status == 1){
//                 notifNo(obj.notif);
//       }
//       if(obj.status == 2){
//               notifYesAuto(obj.notif);
//               window.location.href = '../index';
//       }
//     })
//     .fail(function(res) {
//       alert("Error");
//       console.log("Error", res.responseText);

//     });
//   });

// })

$(document).ready(() => {
  var url_data=$('#attachment1').val();
  showPDF($('#attachment1').val());
  $('#pageNow').val(1);

  $(document).on('click','button#sign_btn',function(e){
    e.preventDefault();
    var pageNow = $('#pageNow').val();
    var llx = $('#llx_result').val();
    var lly = $('#lly_result').val();
    var urx = $('#urx').val();
    var ury = $('#ury').val();
    var type_surat = $('#type_surat').val();
    var url_act_sign_doc  = site_url+"admin_tenant/"+type_surat+"/act_sign_doc";
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
    showPage(1);
}

async function showPage(page_no) {
    _PAGE_RENDERING_IN_PROGRESS = 1;
    _CURRENT_PAGE = page_no;    
    document.querySelector("#pdf-next").disabled = true;
    document.querySelector("#pdf-prev").disabled = true;
    document.querySelector("#pdf-first").disabled = true;
    document.querySelector("#pdf-last").disabled = true;    
    document.querySelector("#pdf-canvas").style.display = 'none';
    document.querySelector("#page-loader").style.display = 'block';
    document.querySelector("#pdf-current-page").innerHTML = page_no;
    document.querySelector("#pdf-input").innerHTML = page_no;
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
    document.querySelector("#pdf-first").disabled = false;
    document.querySelector("#pdf-last").disabled = false;
    document.querySelector("#pdf-canvas").style.display = 'block';
    document.querySelector("#page-loader").style.display = 'none';
}


document.querySelector("#pdf-prev").addEventListener('click', function() {
    if(_CURRENT_PAGE != 1){
        showPage(--_CURRENT_PAGE);
        $('#pageNow').val(_CURRENT_PAGE)
    }
});

document.querySelector("#pdf-first").addEventListener('click', function() {
    if(_CURRENT_PAGE != 1){
        showPage(1);
        $('#pageNow').val(1)
    }
});


document.querySelector("#pdf-next").addEventListener('click', function() {
    if(_CURRENT_PAGE != _TOTAL_PAGES){
        showPage(++_CURRENT_PAGE);
        $('#pageNow').val(_CURRENT_PAGE)
    }
});

document.querySelector("#pdf-last").addEventListener('click', function() {
    if(_CURRENT_PAGE != _TOTAL_PAGES){
        showPage(_TOTAL_PAGES);
        $('#pageNow').val(_TOTAL_PAGES)
    }
});

document.querySelector("#pdf-gotopage").addEventListener('click', function() {
    _GOTO_PAGE=$('#pdf-input').val();
    if(_GOTO_PAGE <= _TOTAL_PAGES){
        showPage(_GOTO_PAGE);
    }
});