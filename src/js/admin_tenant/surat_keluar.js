$(document).ready(function(){
	var url_ctrl = site_url+"admin_tenant/surat_keluar/";
	// var url_form_posisi	= site_url+"admin_tenant/surat_keluar/posisi_sign/";
	var url_tbl 		= site_url+"admin_tenant/surat_keluar/table";
	var url_add 		= site_url+"admin_tenant/surat_keluar/add";
	var url_edit 		= site_url+"admin_tenant/surat_keluar/edit/";
	var url_act_add 	= site_url+"admin_tenant/surat_keluar/act_add";
	var url_act_edit 	= site_url+"admin_tenant/surat_keluar/act_edit";
	var url_act_del 	= site_url+"admin_tenant/surat_keluar/act_del";
	var url_form_attach	= site_url+"admin_tenant/surat_keluar/upload_attach/";
	var url_act_attach	= site_url+"admin_tenant/surat_keluar/act_upload_attach";
	// var url_form_send	= site_url+"admin/surat_keluar/send_mail/";
	// var url_act_send	= site_url+"admin/surat_keluar/act_send_mail/";
	// var url_status		= site_url+"admin/surat_keluar/view_status/";
	// var url_act_status	= site_url+"admin/surat_keluar/act_status";
	// var url_form_vendor	= site_url+"admin/surat_keluar/upload_vendor/";
	// var url_upload_vendor	= site_url+"admin/surat_keluar/upload_vendor_act_btn";
	var url_act_sign	= site_url+"admin_tenant/surat_keluar/act_sign_document/";
	// var url_form_sign	= site_url+"admin_tenant/surat_keluar/sign_document/";

	var table = $('#tbl_arsip').DataTable({
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
			        	// table.ajax.reload(null, false);
			        	window.location.href = 'sign_pdf/'+id_surat;
			        });
			    }
            });
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	$(document).on('click','a.btn.posisi_act_btn',function(e){
		var id_surat = $(this).attr('data-id');
		window.location.href = 'sign_pdf/'+id_surat;
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
});