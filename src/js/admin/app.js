$(document).ready(() => {
  'use strict';
  var url_ctrl = site_url+"admin/surat_masuk/";
  // var url_act_sign_doc  = site_url+"admin/surat_masuk/act_sign_doc";
  var documentToolbar = $('.document-toolbar')
  var contentHeader = $('.content-header')
  var distance = contentHeader.outerHeight()
  $('.document-toolbar-container-sticky').css('height', documentToolbar.outerHeight())
  
  $(window).scroll(() => {
    if ($(window).scrollTop() >= distance) {
      documentToolbar.addClass('document-toolbar-sticky')
    } else {
      documentToolbar.removeClass('document-toolbar-sticky')
    }
    var scrollDistance = $(window).scrollTop()
    var result = Math.abs(Math.round(scrollDistance / 1227));
    if (result == 0) {
      result = 1
    }
    $('#pageCurrent').text(result)
  })
  if ($(window).scrollTop() > distance) {
    documentToolbar.addClass('document-toolbar-sticky')
  }

  $(document).on('click','button#sign_btn',function(e){
    e.preventDefault();
    var llx = $('#llx_result').val();
    var lly = $('#lly_result').val();
    var urx = $('#urx').val();
    var ury = $('#ury').val();
    var type_surat = $('#type_surat').val();
    var url_act_sign_doc  = site_url+"admin/"+type_surat+"/act_sign_doc";
    $.post(url_act_sign_doc,{
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