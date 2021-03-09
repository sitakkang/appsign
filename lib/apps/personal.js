$(document).ready(function(){

	var url_tbl 		= site_url+"apps/personal/tbl_inbox";
	var url_add 		= site_url+"apps/personal/add";
	var url_edit 		= site_url+"apps/personal/edit";
	var url_act_add 	= site_url+"apps/personal/act_add";
	var url_act_edit 	= site_url+"apps/personal/act_edit";
	var url_act_del 	= site_url+"apps/personal/act_del";
	var url_form_disposisi	= site_url+"apps/personal/form_disposisi/";
	var url_act_disposisi	= site_url+"apps/personal/act_disposisi";
	var url_act_done	= site_url+"apps/personal/act_done";

	var table = $('#tbl_inbox').DataTable({
        "ajax": url_tbl,
        "deferRender": true,
        "order": [["0", "desc"]]
    });

    // Preview Btn
	$(document).on('click','a.btn.btn_preview_mail',function(e){
		e.preventDefault();
		var id_label = $(this).attr('data-id');
		$.magnificPopup.open({
			items: {
				src: site_url+id_label
			},
			type: 'image'
		});
	});

	// Form Disposisi
	$(document).on('click','a.btn.disposisi_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		$.get(url_form_disposisi+id_surat)
		.done(function(view) {
			$('#MyModalTitle').html('<b>Disposisi</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="save_disposisi_act_btn">Kirim</button>');
			$("div#MyModalContent").html(view);
			$("div#MyModal").modal('show');
		})
		.fail(function(res) {
			alert("Error");
			console.log("Error", res.responseText);
		});
	});

	// Sending disposisi
	$(document).on('click','#save_disposisi_act_btn',function(e){
		e.preventDefault();
		if($('select#disposisi :selected').length > 0){
	        var selectedsend = [];
	        $('select#disposisi :selected').each(function(i, selected){
	            selectedsend[i] = $(selected).val();
	        });
        }else{ selectedsend = ''; }
		$.post(url_act_disposisi,{
			id_surat:$("input[name*='id_surat']").val(),
			disposisi:JSON.stringify(selectedsend),
			catatan:$("textarea[name*='catatan']").val()
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

	// Done btn
	$(document).on('click','a.btn.done_act_btn',function(e){
		e.preventDefault();
		var id_surat = $(this).attr('data-id');
		swal({
			title: 'Anda yakin ?',
			text: 'Akan merubah status surat ini menjadi selesai ?',
			type: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, selesaikan !',
			cancelButtonText: 'Tidak, batalkan !',
			confirmButtonClass: 'btn btn-primary',
			cancelButtonClass: 'btn btn-danger',
			buttonsStyling: false
		}).then(function () {
			$.ajax({
					method:"POST",
					url:url_act_done,
					data:{id_surat:id_surat}
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
				.fail(function() {
					alert("Error");
				});
		},function(dismiss) {
			if (dismiss === 'cancel') {
				$("div#MyModal").modal('hide');
				notifCancleAuto('Proses dibatalkan.');
			}
		})
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
    	$("div#MyModalFooter").empty();
    	$("div.modal-body").empty();
	});
});