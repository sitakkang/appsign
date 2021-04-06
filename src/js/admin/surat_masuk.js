$(document).ready(function(){
	var url_ctrl = site_url+"admin/surat_masuk/";
	var url_form_send	= site_url+"admin/surat_masuk/send_mail/";
	var url_form_sign	= site_url+"admin/surat_masuk/sign_document/";
	var url_form_posisi	= site_url+"admin/surat_masuk/posisi_sign/";
	var url_form_vendor	= site_url+"admin/surat_masuk/upload_vendor/";
	var url_edit 		= site_url+"admin/surat_masuk/edit/";
	var url_act_add 	= site_url+"admin/surat_masuk/act_add";
	var url_add 		= site_url+"admin/surat_masuk/add";
	var url_act_edit 	= site_url+"admin/surat_masuk/act_edit";
	var url_act_del 	= site_url+"admin/surat_masuk/act_del";
	var url_act_status	= site_url+"admin/surat_masuk/act_status";
	var url_status		= site_url+"admin/surat_masuk/view_status/";
	var url_form_attach	= site_url+"admin/surat_masuk/upload_attach/";
	var url_act_attach	= site_url+"admin/surat_masuk/act_upload_attach";
	var url_act_send	= site_url+"admin/surat_masuk/act_send_mail/";
	var url_act_set_posisi= site_url+"admin/surat_masuk/act_set_posisi/";
	var url_upload_vendor	= site_url+"admin/surat_masuk/upload_vendor_act_btn";
	var url_act_sign	= site_url+"admin/surat_masuk/act_sign_document/";

	var table = $('#tabel_custom').DataTable({
        "ajax": url_ctrl+'table',
        "deferRender": true,
        "order": [["0", "desc"]],
        "scrollX" : true,
		"scrollCollapse" : true,
		"fixedColumns" :   true,
        "fixedColumns" :   {
            "rightColumns" : 1
        },
        "columnDefs": [
			{ "orderable": false, "targets": [1,2] },
			{ "className": "text-nowrap", "targets": [ 1,2,3 ] }
    	]
    });

    $(document).on('click','a.btn.posisi_act_btn',function(e){
		var id_surat = $(this).attr('data-id');
		window.location.href = 'sign_pdf/'+id_surat;
	});

	$(document).on('click','a.btn.upload_vendor_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_form_vendor+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Choose Vendor</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="upload_vendor_act_btn">Sent</button>');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	// $(document).on('click','a.btn.posisi_act_btn',function(e){
	// 	e.preventDefault();
	// 	var id_surat = $(this).attr('data-id');
	// 	$.get(url_form_posisi+id_surat)
	// 	.done(function(view) {
	// 		$('#MyModalTitle').html('<b>Set Posisi</b>');
	// 		$('div.modal-dialog').addClass('modal-sm');
	// 		$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="set_posisi_act_btn">Set</button>');
	// 		$("div#MyModalContent").html(view);
	// 		$("div#MyModal").modal('show');
	// 	})
	// 	.fail(function(res) {
	// 		alert("Error");
	// 		console.log("Error", res.responseText);
	// 	});
	// });

	// Set Posisi
	$(document).on('click','#set_posisi_act_btn',function(e){
		e.preventDefault();
		if($('select#posisi :selected').length > 0){
	        var selectedsend = [];
	        $('select#posisi :selected').each(function(i, selected){
	            selectedsend[i] = $(selected).val();
	        });
        }else{ selectedsend = ''; }
		$.post(url_act_set_posisi,{
			id_surat:$("input[name*='id_surat']").val(),
			posisi:JSON.stringify(selectedsend)
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAuto(obj.notif);
            	table.ajax.reload(null, false);
			}
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	// Upload Vendor
	$(document).on('click','#upload_vendor_act_btn',function(e){
		e.preventDefault();
		if($('select#vendor :selected').length > 0){
	        var selectedvendor = [];
	        $('select#vendor :selected').each(function(i, selected){
	            selectedvendor[i] = $(selected).val();
	        });
        }else{ selectedsend = ''; }
		$.post(url_upload_vendor,{
			id_surat:$("input[name*='id_surat']").val(),
			vendor:JSON.stringify(selectedvendor)
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAuto(obj.notif);
            	table.ajax.reload(null, false);
			}
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

    $(document).on('click','a.btn.send_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_form_send+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Kirim Surat</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="save_send_act_btn">Kirim</button>');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	// Sending mail
	$(document).on('click','#save_send_act_btn',function(e){
		e.preventDefault();
		if($('select#ditujukan :selected').length > 0){
	        var selectedsend = [];
	        $('select#ditujukan :selected').each(function(i, selected){
	            selectedsend[i] = $(selected).val();
	        });
        }else{ selectedsend = ''; }
		$.post(url_act_send,{
			id_surat:$("input[name*='id_surat']").val(),
			ditujukan:JSON.stringify(selectedsend)
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAuto(obj.notif);
            	table.ajax.reload(null, false);
			}
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	// sign document
	$(document).on('click','a.btn.sign_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_form_sign+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Sign Document</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="sign_document_act_btn">Sign</button>');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	$(document).on('click','#sign_document_act_btn',function(e){
		e.preventDefault();
		if($('select#ditujukan :selected').length > 0){
	        var selectedsend = [];
	        $('select#ditujukan :selected').each(function(i, selected){
	            selectedsend[i] = $(selected).val();
	        });
        }else{ selectedsend = ''; }
		$.post(url_act_sign,{
			id_surat:$("input[name*='id_surat']").val(),
			ditujukan:JSON.stringify(selectedsend)
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAuto(obj.notif);
            	table.ajax.reload(null, false);
            	// window.location.href = 'surat_masuk/proses/'+obj.id;
            	window.location.href = obj.url_api;
			}
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	$(document).on('click','a.edit_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_edit+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Edit</b>');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="save_edit_btn">Simpan</button>');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');
			$('input.tanggal').datetimepicker({
				timepicker:false,
				format:'Y-m-d',
				scrollMonth:false,
				scrollTime:false,
				scrollInput:false,
			});
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	$(document).on('click','button#save_edit_btn',function(e){
		e.preventDefault();
		$.post(url_act_edit,{
			id_surat:$("input[name*='id_surat']").val(),
			no_surat:$("input[name*='no_surat']").val(),
			perihal:$("textarea[name*='perihal']").val(),
			jenis:$("select[name*='jenis']").val(),
			pengirim:$("textarea[name*='pengirim']").val(),
			penerima:$("input[name*='penerima']").val(),
			tgl_terima:$("input[name*='tgl_terima']").val(),
			catatan:$("textarea[name*='catatan']").val(),
			melalui:$("select[name*='melalui']").val()
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAuto(obj.notif);
            	table.ajax.reload(null, false);
			}
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);

		});
	});
	
	// Add Button
	$(document).on('click','button#add_btn',function(e){
		e.preventDefault();
		$.get(url_add)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Rekap Surat</b>');
			//$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="save_add_btn">Simpan</button>');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');
			$('input.tanggal').datetimepicker({
				timepicker:false,
				format:'Y-m-d',
				scrollMonth:false,
				scrollTime:false,
				scrollInput:false,
			});
		})
		.fail(function() {
			alert("Error");
		});
	});

	$(document).on('click','button#save_add_btn',function(e){
		e.preventDefault();
		$.post(url_act_add,{
			no_surat:$("input[name*='no_surat']").val(),
			perihal:$("textarea[name*='perihal']").val(),
			jenis:$("select[name*='jenis']").val(),
			pengirim:$("textarea[name*='pengirim']").val(),
			penerima:$("input[name*='penerima']").val(),
			tgl_terima:$("input[name*='tgl_terima']").val(),
			catatan:$("textarea[name*='catatan']").val(),
			melalui:$("select[name*='melalui']").val(),
			flaging:$('input#flag').attr('value')
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAuto(obj.notif);
            	table.ajax.reload(null, false);
			}
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);

		});
	});

	// Delete Button
	$(document).on('click','a.delete_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		var no_surat = $(this).attr('data-surat');
		swal({
			title: 'Anda yakin ?',
			text: 'Data dengan nomor surat '+no_surat+' akan di hapus ?',
			type: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, hapus !',
			cancelButtonText: 'Tidak, batalkan !',
			confirmButtonClass: 'btn btn-danger',
			cancelButtonClass: 'btn btn-primary',
			buttonsStyling: false
		}).then(function () {
			$.post(url_act_del,{
				id_surat:id_surat
			})
			.done(function(result) {
				var obj = jQuery.parseJSON(result);
				if(obj.status == 1){
					notifNo(obj.notif);
				}
				if(obj.status == 2){
					notifYesAuto(obj.notif);
					table.ajax.reload(null, false);
				}
			})
			.fail(function(res) {
				alert("Error");
				console.log("Error", res.responseText);
			});
		},function(dismiss) {
			if (dismiss === 'cancel') {
				$("div#MyModal").modal('hide');
				notifCancleAuto('Proses hapus di batalkan.');
			}
		})
	});

	// Status Button
	$(document).on('click','span.label.popup',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_status+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Status</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="save_status_btn">Simpan</button>');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});


	$(document).on('click','button#save_status_btn',function(e){
		e.preventDefault();
		$.post(url_act_status,{
			id_surat:$("input[name*='id_surat']").val(),
			status:$("select[name*='status']").val()
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
				notifNo(obj.notif);
			}
			if(obj.status == 2){
				$("div#MyModal").modal('hide');
				notifYesAuto(obj.notif);
				table.ajax.reload(null, false);
			}
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	// Upload attachment
	$(document).on('click','a.btn.upload_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_form_attach+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Upload Attachment</b>');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');
			$("#UploadAttach").dropzone({ 
                url: url_act_attach,
                maxFiles: 15,
                maxFilesize: 35,
                acceptedFiles: 'image/*,.pdf',
				init: function() {
					this.on("sending", function(file, xhr, formData) {
						formData.append("id_surat", id_surat);
					});
			        this.on("success", function() {
			        	table.ajax.reload(null, false);
			        });
			    }
            });
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	// Act Sending Mail
	$(document).on('click','a.btn.btn_preview_mail',function(e){
		e.preventDefault();
		var id_label = $(this).attr('data-url');
		var id_tipe = $(this).attr('data-tipe');
		if(id_tipe == 1){

			view = '<div><object data="'+site_url+'upload/masuk/'+id_label+'" width="100%" height="500px"><p>Browser anda tidak memiliki ekstensi untuk membuka file ini secara langsung. Disarankan untuk memasang browser terbaru (Google Chrome, Mozilla Firefox, Safari, dll) dan memasang aplikasi pembaca file PDF (Adobe Acrobat Reader, PDF Viewer, dll) di perangkat anda. Untuk mengunduh file ini <a href="'+site_url+'upload/masuk/'+id_label+'"> Click ditautan ini !</a></p></object></div>';

			$('#MyModalTitle').html('<b>Attachment</b>');
			$('div.modal-dialog').addClass('modal-lg');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');

		}else{
			$.magnificPopup.open({
				items: {
					src: site_url+'upload/masuk/'+id_label
				},
				type: 'image'
			});
			wheelzoom(document.querySelector('img.mfp-img'));
		}
	});

	// Setting Modal and Sweet Alert 2
	$("div#MyModal").on('shown.bs.modal',function(e){
		e.preventDefault();
    	$('body').removeAttr('style');
    	$('.selectpicker').selectpicker('refresh');
	});
	$("div#MyModal").on('hidden.bs.modal',function(e){
		e.preventDefault();
    	$('body').removeAttr('style');
    	$('div.modal-dialog').removeClass('modal-lg');
    	$('div.modal-dialog').removeClass('modal-sm');
    	$("div.modal-body").empty();
    	$("div#MyModalFooter").empty();
	});

});