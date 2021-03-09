$(document).ready(function(){
	
	var url_tbl 		= site_url+"admin/level/tabel";
	var url_add 		= site_url+"admin/level/add";
	var url_edit 		= site_url+"admin/level/edit";
	var url_act_add 	= site_url+"admin/level/act_add";
	var url_act_edit 	= site_url+"admin/level/act_edit";
	var url_act_del 	= site_url+"admin/level/act_del";

	var table = $('#tabel_custom').DataTable({
        "ajax": url_tbl,
        "deferRender": true,
        "order": [["0", "desc"]]
    });

    // Select Row Table
	$('#tabel_custom tbody').on('click', 'tr', function(e){
     	e.preventDefault();
    	if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(this).addClass('active');
        }else{
            table.$('tr.active').removeClass('active');
            $(this).addClass('active');
        }
    	//rowIndex = table.row(this).index();
		rowId = table.row(this).id();
    	$('#popup_menu').css({left:e.pageX+"px",top:e.pageY+"px"}).show("fast", function(){
    		$("button#edit_btn").attr('data-id', rowId);
    		$("button#delete_btn").attr('data-id', rowId);
    		$("button#reset_btn").attr('data-id', rowId);
    	});
    });

    $(document).on('click', function(e){
    	if(e.target.nodeName !== "TD"){
    		$('#popup_menu').hide();
    		$('#popup_menu').removeAttr('style');
    	}
	});

	// Add Button
	$(document).on('click','#add_btn',function(e){
		e.preventDefault();
		$.ajax({
				method:"GET",
				cache:false,
				url:url_add
			})
			.done(function(view) {
				$('#MyModalTitle').html('<b>Tambah</b>');
				$('div.modal-dialog').addClass('modal-sm');
				$("div#MyModalContent").html(view);
				$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_add_btn">Simpan</button>');
				$("div#MyModal").modal('show');
			})
			.fail(function() {
				alert("Error");
			});
	});

	$(document).on('click','#save_add_btn',function(e){
		e.preventDefault();
		var nama_level = $("input#name").val();
		$.ajax({
				method:"POST",
				url:url_act_add,
				cache:false,
				data: {
					name:nama_level
				}
			})
			.done(function(result) {
				var obj = jQuery.parseJSON(result);
				if(obj.status == 1){
	                notifNoHtml(obj.notif);
				}
				if(obj.status == 2){
	                $("div#MyModal").modal('hide');
                	notifYesAutoHtml(obj.notif);
					table.row.add({
                		"DT_RowId" : obj.lastid,
					    "0" : obj.lastid,
					    "1" : nama_level
			        }).draw(false);
				}
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Edit Button
	$(document).on('click','#edit_btn',function(e){
		e.preventDefault();
		$.ajax({
				method:"GET",
				url:url_edit,
				cache:false,
				data:{id_tbl:$(this).attr('data-id')}
			})
			.done(function(view) {
				$('#MyModalTitle').html('<b>Ubah</b>');
				$('div.modal-dialog').addClass('modal-sm');
				$("div#MyModalContent").html(view);
				$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_edit_btn">Ubah</button>');
				$("div#MyModal").modal('show');
			})
			.fail(function() {
				alert("Error");
			});
	});

	$(document).on('click','#save_edit_btn',function(e){
		e.preventDefault();
		var new_name = $("input#name").val();
		$.ajax({
				method:"POST",
				url:url_act_edit,
				cache:false,
				data: {
					id_tbl:$("input#id_level").val(),
		            name:new_name,
		            name_old:$("input#name_old").val()
				}
			})
			.done(function(result) {
				var obj = jQuery.parseJSON(result);
				if(obj.status == 1){
	                notifNoHtml(obj.notif);
				}
				if(obj.status == 2){
	                $("div#MyModal").modal('hide');
	                notifYesAutoHtml(obj.notif);
	                var rowEdit = table.row('tr.active').data();
					rowEdit[1] = new_name;
					table.row('tr.active').data(rowEdit).invalidate();
				}
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Delete Button
	$(document).on('click','#delete_btn',function(e){
		e.preventDefault();
		rowSelect = table.row('tr.active').data();
		var id_tbl = rowSelect[0];
		var id_name = rowSelect[1];
		swal({
			title: 'Anda yakin ?',
			text: 'Data '+id_name+' akan di hapus ?',
			type: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, hapus !',
			cancelButtonText: 'Tidak, batalkan !',
			confirmButtonClass: 'btn btn-danger',
			cancelButtonClass: 'btn btn-primary',
			buttonsStyling: false
		}).then(function () {
			$.ajax({
					method:"POST",
					url:url_act_del,
					cache:false,
					data: {
						id_tbl:id_tbl,
						id_name:id_name
					}
				})
				.done(function(result) {
					var obj = jQuery.parseJSON(result);
					if(obj.status == 1){
		                notifNoHtml(obj.notif);
					}
					if(obj.status == 2){
		                $("div#MyModal").modal('hide');
						notifYesAutoHtml(obj.notif);
						table.row('tr.active').remove().draw(false);
					}
				})
				.fail(function() {
					alert("Error");
				});
		},function(dismiss) {
			if (dismiss === 'cancel') {
				$("div#MyModal").modal('hide');
				notifCancleAuto('Proses hapus di batalkan.');
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
    	$("div.modal-body").empty();
	});

});