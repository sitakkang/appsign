$(document).ready(function(){
	
	var url_upload 		= site_url+"admin/config/upload";
	var url_edit 		= site_url+"admin/config/edit";
	var url_act_upload 	= site_url+"admin/config/act_upload/";
	var url_act_edit 	= site_url+"admin/config/act_edit";

	$.fn.LoadContent = function(){this.load(url_edit);}

	$("div#load_tabel").LoadContent();

	// Upload Button
	$(document).on('click','a.btn_upload_app',function(e){
		e.preventDefault();
		var name = $(this).data('id');
		var type = $(this).data('type');
		$.ajax({
				method:"GET",
				cache:false,
				url:url_upload
			})
			.done(function(view) {
				$('#MyModalTitle').html('<b>Upload</b>');
				$('div.modal-dialog').addClass('modal-sm');
				$('div#MyModalContent').html(view);
				$("#UploadLogo").dropzone({ 
                    url: url_act_upload+name,
                    maxFiles: 1,
                    maxFilesize: 5,
                    acceptedFiles: type,
					init: function() {
				        this.on("success", function() {
				            $("div#load_tabel").LoadContent();
				        });
				    }
                });
                $("div#MyModal").modal('show');
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Act Edit
	$(document).on('click','button#btn_save_config',function(e){
		e.preventDefault();
		var id_tbl = $("input#id_tbl").val();
		var title_front = $("input#title_front").val();
		var title_back = $("input#title_back").val();
		var email_ = $("input#email_").val();
		var footer_ = $("input#footer_").val();
		var meta_description = $("textarea#meta_description").val();
		var meta_keyword = $("textarea#meta_keyword").val();
		swal({
			title: 'Anda yakin ?',
			text: 'Data pengaturan aplikasi akan diperbarui ?',
			type: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, simpan !',
			cancelButtonText: 'Tidak, batalkan !',
			confirmButtonClass: 'btn btn-primary',
			cancelButtonClass: 'btn btn-danger',
			buttonsStyling: false
		}).then(function () {
			$.ajax({
					method:"POST",
					url:url_act_edit,
					cache:false,
					data: {
						id_tbl:id_tbl,
						title_front:title_front,
						title_back:title_back,
						email_:email_,
						footer_:footer_,
						meta_description:meta_description,
						meta_keyword:meta_keyword
					}
				})
				.done(function(result) {
					var obj = jQuery.parseJSON(result);
					if(obj.status == 1){
		                notifNoHtml(obj.notif);
					}
					if(obj.status == 2){
						notifYesAutoHtml(obj.notif);
						$("div#load_tabel").LoadContent();
					}
				})
				.fail(function() {
					alert("Error");
				});
		},function(dismiss) {
			if (dismiss === 'cancel') {
				notifCancleAuto('Proses dibatalkan.');
				$("div#load_tabel").LoadContent();
			}
		})
	});

	// Setting Modal and Sweet Alert 2
	$("div#MyModal").on('shown.bs.modal',function(e){
		e.preventDefault();
    	$('body').removeAttr('style');
	});
	$("div#MyModal").on('hidden.bs.modal',function(e){
		e.preventDefault();
    	$('body').removeAttr('style');
    	$('div.modal-dialog').removeClass('modal-lg');
    	$('div.modal-dialog').removeClass('modal-sm');
	});

});