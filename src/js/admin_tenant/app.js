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
    _CANVAS = document.querySelector('#pdf-canvas');

// initialize and load the PDF
async function showPDF(pdf_url) {
    document.querySelector("#pdf-loader").style.display = 'block';

    // get handle of pdf document
    try {
        _PDF_DOC = await pdfjsLib.getDocument({ url: pdf_url });
    }
    catch(error) {
        alert(error.message);
    }

    // total pages in pdf
    _TOTAL_PAGES = _PDF_DOC.numPages;
    
    // Hide the pdf loader and show pdf container
    document.querySelector("#pdf-loader").style.display = 'none';
    document.querySelector("#pdf-contents").style.display = 'block';
    document.querySelector("#pdf-total-pages").innerHTML = _TOTAL_PAGES;

    // show the first page
    showPage(1);
}

// load and render specific page of the PDF
async function showPage(page_no) {
    _PAGE_RENDERING_IN_PROGRESS = 1;
    _CURRENT_PAGE = page_no;

    // disable Previous & Next buttons while page is being loaded
    document.querySelector("#pdf-next").disabled = true;
    document.querySelector("#pdf-prev").disabled = true;
    document.querySelector("#pdf-first").disabled = true;
    document.querySelector("#pdf-last").disabled = true;

    // while page is being rendered hide the canvas and show a loading message
    document.querySelector("#pdf-canvas").style.display = 'none';
    document.querySelector("#page-loader").style.display = 'block';

    // update current page
    document.querySelector("#pdf-current-page").innerHTML = page_no;
    
    // get handle of page
    try {
        var page = await _PDF_DOC.getPage(page_no);
    }
    catch(error) {
        alert(error.message);
    }

    // original width of the pdf page at scale 1
    var pdf_original_width = page.getViewport(1).width;
    
    // as the canvas is of a fixed width we need to adjust the scale of the viewport where page is rendered
    var scale_required = _CANVAS.width / pdf_original_width;

    // get viewport to render the page at required scale
    var viewport = page.getViewport(scale_required);

    // set canvas height same as viewport height
    _CANVAS.height = viewport.height;

    // setting page loader height for smooth experience
    document.querySelector("#page-loader").style.height =  _CANVAS.height + 'px';
    document.querySelector("#page-loader").style.lineHeight = _CANVAS.height + 'px';

    var render_context = {
        canvasContext: _CANVAS.getContext('2d'),
        viewport: viewport
    };
        
    // render the page contents in the canvas
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

// click on the "Previous" page button
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

// click on the "Next" page button
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