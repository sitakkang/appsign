$(document).ready(function(){

	var url_tbl 		= site_url+"apps/surat_keluar/table";
	var url_add 		= site_url+"apps/surat_keluar/add";
	var url_edit 		= site_url+"apps/surat_keluar/edit/";
	var url_act_add 	= site_url+"apps/surat_keluar/act_add";
	var url_act_edit 	= site_url+"apps/surat_keluar/act_edit";
	var url_act_del 	= site_url+"apps/surat_keluar/act_del";
	var url_form_attach	= site_url+"apps/surat_keluar/upload_attach/";
	var url_act_attach	= site_url+"apps/surat_keluar/act_upload_attach";
	var url_form_send	= site_url+"apps/surat_keluar/send_mail/";
	var url_act_send	= site_url+"apps/surat_keluar/act_send_mail/";
	var url_status		= site_url+"apps/surat_keluar/view_status/";
	var url_act_status	= site_url+"apps/surat_keluar/act_status";

	var data_flag = $('input#flag').attr('value');

	var table = $('#tbl_arsip').DataTable({
        "ajax"       : url_tbl+'/'+data_flag,
        "deferRender": true,
        "order"      : [["0", "desc"]],
		"scrollX" : true,
		"scrollCollapse" : true,
		"fixedColumns" :   true,
        "fixedColumns" :   {
            "rightColumns" : 1
        },
        "columnDefs": [
    		{ "orderable": false, "targets": [1,2] },
			{ "className": "nowrap", "targets": [ 1,2,3 ] }
    	]
	});

	// Add Button Option Table
	$(document).on('click','button#add_btn',function(e){
		e.preventDefault();
		data_flag = $(this).attr('data-flag');
		$('button#add_btn*').removeClass('btn btn-primary').addClass('btn btn-default');
		$(this).attr('class', 'btn btn-primary');
		$('input#flag').attr('value', data_flag);
		table.ajax.url(url_tbl+'/'+data_flag).load();
	});
	
	// Add Button
	$(document).on('dblclick','button#add_btn',function(e){
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
                notifNoHtml(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAutoHtml(obj.notif);
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

	// Sending Mail
	$(document).on('click','a.btn.send_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_form_send+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Surat</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="save_send_act_btn">Simpan</button>');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');
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

	// Sending mail
	$(document).on('click','#save_send_act_btn',function(e){
		e.preventDefault();
		if($('select#disetujui :selected').length > 0){
	        var selectedsend = [];
	        $('select#disetujui :selected').each(function(i, selected){
	            selectedsend[i] = $(selected).val();
	        });
        }else{ selectedsend = ''; }
		$.post(url_act_send,{
			id_surat:$("input[name*='id_surat']").val(),
			disetujui:JSON.stringify(selectedsend)
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNoHtml(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAutoHtml(obj.notif);
            	table.ajax.reload(null, false);
			}
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
			tujuan:$("textarea[name*='tujuan']").val(),
			diusulkan:$("input[name*='diusulkan']").val(),
			tgl_kirim:$("input[name*='tgl_kirim']").val(),
			catatan:$("textarea[name*='catatan']").val(),
			melalui:$("select[name*='melalui']").val()
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNoHtml(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAutoHtml(obj.notif);
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
					notifNoHtml(obj.notif);
				}
				if(obj.status == 2){
					notifYesAutoHtml(obj.notif);
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
				notifNoHtml(obj.notif);
			}
			if(obj.status == 2){
				$("div#MyModal").modal('hide');
				notifYesAutoHtml(obj.notif);
				table.ajax.reload(null, false);
			}
		})
		.fail(function(res) {
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
});