$(document).ready(function(){
	var url_ctrl = site_url+"surat_keluar/surat_keluar_admin/";
	var url_form_posisi	= site_url+"surat_keluar/surat_keluar_admin/posisi_sign/";
	var url_tbl 		= site_url+"surat_keluar/surat_keluar_admin/table";
	var url_add 		= site_url+"surat_keluar/surat_keluar_admin/add";
	var url_edit 		= site_url+"surat_keluar/surat_keluar_admin/edit/";
	var url_act_add 	= site_url+"surat_keluar/surat_keluar_admin/act_add";
	var url_act_edit 	= site_url+"surat_keluar/surat_keluar_admin/act_edit";
	var url_act_del 	= site_url+"surat_keluar/surat_keluar_admin/act_del";
	var url_form_attach	= site_url+"surat_keluar/surat_keluar_admin/upload_attach/";
	var url_act_attach	= site_url+"surat_keluar/surat_keluar_admin/act_upload_attach";
	var url_form_send	= site_url+"surat_keluar/surat_keluar_admin/send_mail/";
	var url_act_send	= site_url+"surat_keluar/surat_keluar_admin/act_send_mail/";
	var url_status		= site_url+"surat_keluar/surat_keluar_admin/view_status/";
	var url_act_status	= site_url+"surat_keluar/surat_keluar_admin/act_status";
	var url_form_vendor	= site_url+"surat_keluar/surat_keluar_admin/upload_vendor/";
	var url_upload_vendor	= site_url+"surat_keluar/surat_keluar_admin/upload_vendor_act_btn";
	var url_act_sign	= site_url+"surat_keluar/surat_keluar_admin/act_sign_doc/";
	var url_form_sign	= site_url+"surat_keluar/surat_keluar_admin/sign_document/";
	var url_view 		= site_url+"surat_keluar/surat_keluar_admin/detail/";

	function loadingShow() {
        $('#loading').show();
    }

    $(document).on('change','#jenis_ttd',function(e){
		e.preventDefault();
		var jenis_ttd = $("select[id*='jenis_ttd']").val();
		var surat_signer = document.getElementById("surat_signer");
		var surat_content = document.getElementById("surat_content");
		if(jenis_ttd=="Digital"){
			surat_signer.style.display="block";
			surat_content.style.display="block";
		}else if(jenis_ttd=="Manual"){
			surat_signer.style.display="none";
			surat_content.style.display="block";
		}
	});

	var table = $('#tbl_arsip').DataTable({
        "ajax": url_ctrl+'table',
        "deferRender": true,
        "order": [["0", "asc"]]
    });

	$(document).on('click','button#add_btn',function(e){
		e.preventDefault();
		$.get(url_add)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Rekap Surat</b>');
			//$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_add_btn">Simpan</button>');
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
			jenis_ttd:$("select[name*='jenis_ttd_']").val(),
			no_surat:$("input[name*='no_surat']").val(),
			perihal:$("textarea[name*='perihal']").val(),
			jenis:$("select[name*='jenis']").val(),
			signer:$("select[name*='signer']").val(),
			asal_surat:$("select[name*='asal_surat']").val(),
			tujuan:$("textarea[name*='tujuan']").val(),
			diusulkan:$("input[name*='diusulkan']").val(),
			tgl_kirim:$("input[name*='tgl_kirim']").val(),
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

	// View
	$(document).on('click','a.view_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_view+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>View Detail</b>');
			$("div#MyModalFooter").html('');
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


	// Edit Mail
	$(document).on('click','a.edit_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_edit+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Edit</b>');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_edit_btn">Simpan</button>');
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
			signer:$("select[name*='signer']").val(),
			asal_surat:$("select[name*='asal_surat']").val(),
			no_surat:$("input[name*='no_surat']").val(),
			perihal:$("textarea[name*='perihal']").val(),
			jenis:$("select[name*='jenis']").val(),
			tujuan:$("textarea[name*='tujuan']").val(),
			diusulkan:$("input[name*='diusulkan']").val(),
			tgl_kirim:$("input[name*='tgl_kirim']").val(),
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

	// Delete Button
	$(document).on('click','.delete_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		var no_surat = $(this).attr('data-surat');
		swal({
			title: 'Anda yakin ?',
			text: 'Data Surat '+no_surat+' akan di hapus ?',
			type: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, hapus !',
			cancelButtonText: 'Tidak, batalkan !'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					method:"POST",
					url:url_act_del,
					cache:false,
					data: {
						id_surat:id_surat
					}
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
				.fail(function(res){
					alert('Error Response !');
					console.log("responseText", res.responseText);
				});
			}
		});
	});

	// $(document).on('click','a.delete_act_btn',function(e){
	// 	e.preventDefault();
	// 	var id_surat = $(this).attr('data-id');
	// 	var no_surat = $(this).attr('data-surat');
	// 	swal({
	// 		title: 'Anda yakin ?',
	// 		text: 'Data dengan nomor surat '+no_surat+' akan di hapus ?',
	// 		type: 'question',
	// 		showCancelButton: true,
	// 		confirmButtonText: 'Ya, hapus !',
	// 		cancelButtonText: 'Tidak, batalkan !',
	// 		confirmButtonClass: 'btn btn-danger',
	// 		cancelButtonClass: 'btn btn-primary',
	// 		buttonsStyling: false
	// 	}).then(function () {
	// 		$.post(url_act_del,{
	// 			id_surat:id_surat
	// 		})
	// 		.done(function(result) {
	// 			var obj = jQuery.parseJSON(result);
	// 			if(obj.status == 1){
	// 				notifNo(obj.notif);
	// 			}
	// 			if(obj.status == 2){
	// 				notifYesAuto(obj.notif);
	// 				table.ajax.reload(null, false);
	// 			}
	// 		})
	// 		.fail(function(res) {
	// 			alert("Error");
	// 			console.log("Error", res.responseText);
	// 		});
	// 	},function(dismiss) {
	// 		if (dismiss === 'cancel') {
	// 			$("div#MyModal").modal('hide');
	// 			notifCancleAuto('Proses hapus di batalkan.');
	// 		}
	// 	})
	// });

	// Status Button
	$(document).on('click','a.status_act_btn',function(e){
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
	$(document).on('click','a.upload_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		var jenis_ttd = $(this).attr('data-jenis_ttd');
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
			        	// table.ajax.reload(null, false);
			        	if(jenis_ttd=='Digital'){
			        		window.location.href = 'sign_pdf/'+id_surat;
			        	}else{
			        		table.ajax.reload(null, false);
			        	}
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
	$(document).on('click','a.btn_preview_mail',function(e){
		e.preventDefault();
		var id_label = $(this).attr('data-url');
		var id_tipe = $(this).attr('data-tipe');
		if(id_tipe == 1){

			view = '<div><object data="'+site_url+'upload/keluar/'+id_label+'" width="100%" height="500px"><p>Browser anda tidak memiliki ekstensi untuk membuka file ini secara langsung. Disarankan untuk memasang browser terbaru (Google Chrome, Mozilla Firefox, Safari, dll) dan memasang aplikasi pembaca file PDF (Adobe Acrobat Reader, PDF Viewer, dll) di perangkat anda. Untuk mengunduh file ini <a href="'+site_url+'upload/keluar/'+id_label+'"> Click ditautan ini !</a></p></object></div>';

			$('#MyModalTitle').html('<b>Attachment</b>');
			$('div.modal-dialog').addClass('modal-lg');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');

		}else{
			$.magnificPopup.open({
				items: {
					src: site_url+'upload/keluar/'+id_label
				},
				type: 'image'
			});
			wheelzoom(document.querySelector('img.mfp-img'));
		}
	});

	// Sending Mail
	$(document).on('click','a.send_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_form_send+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Surat</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" data-loading-text="Loading..." id="save_send_act_btn">Send</button>');
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
		$(this).html(
        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`
      	);
		// $("#save_send_act_btn").button('loading');
		e.preventDefault();
		var id_surat=$("input[name*='id_surat']").val();
		if($('select#disetujui :selected').length > 0){
	        var selectedsend = [];
	        $('select#disetujui :selected').each(function(i, selected){
	            selectedsend[i] = $(selected).val();
	        });
        }else{ selectedsend = ''; }
        
        // loadingshow();
        
		$.post(url_act_send,{
			id_surat:id_surat,
			disetujui:JSON.stringify(selectedsend)
		})
		.done(function(result) {
			// loadingHide();
			span = $(this).find('span'),
		    text = span.text();

		    span.remove();
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
				$('body').append('<div style="" id="loadingDiv"><div class="loader">Loading...</div></div>');
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAuto(obj.notif);
            	table.ajax.reload(null, false);
			}
		})
		.fail(function(res) {
			span = $(this).find('span'),
		    text = span.text();

		    span.remove();
			alert("Error");
			console.log("Error", res.responseText);
		});
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

	// $(document).on('click','a.btn.posisi_act_btn',function(e){
	// 	var id_surat = $(this).attr('data-id');
	// 	alert(id_surat);
	// 	// window.location.href = 'sign_pdf/'+id_surat;
	// });

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

	$(document).on('click','#sign_document_act_btn',function(e){
		e.preventDefault();
		if($('select#disetujui :selected').length > 0){
	        var selectedsend = [];
	        $('select#disetujui :selected').each(function(i, selected){
	            selectedsend[i] = $(selected).val();
	        });
        }else{ selectedsend = ''; }
		$.post(url_act_sign,{
			id_surat:$("input[name*='id_surat']").val(),
			disetujui:JSON.stringify(selectedsend)
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
});