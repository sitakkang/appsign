$(document).ready(function(){

	var url_tbl 		= site_url+"apps/personal/tbl_disposisi";
	var url_act_done	= site_url+"apps/personal/act_done_disposisi";

	var table = $('#tbl_disposisi').DataTable({
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