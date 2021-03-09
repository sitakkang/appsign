$(document).ready(function(){

	var url_ctrl = site_url+"admin/level/";

	var table = $('#tabel_custom').DataTable({
        "ajax": url_ctrl+'tabel',
        "deferRender": true,
        "order": [["0", "desc"]]
    });

    // Select Row Table
	$('#tabel_custom tbody').on('click', 'tr', function(e){
     	e.preventDefault();
    	if($(this).hasClass('actived')){
			$(this).removeClass('actived');
			$(this).addClass('actived');
        }else{
            table.$('tr.actived').removeClass('actived');
            $(this).addClass('actived');
        }
    	//rowIndex = table.row(this).index();
		rowId = table.row(this).id();
		leftWidht = e.pageX-50;
    	$('#popup_menu').css({left:leftWidht+"px",top:e.pageY+"px"}).show("fast", function(){
    		$("button#edit_btn").attr('data-id', rowId);
    		$("button#delete_btn").attr('data-id', rowId);
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
			url:url_ctrl+'add'
		})
		.done(function(view) {
			$('#MyModalTitle').html('<b>Tambah</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalContent").html(view);
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_add_btn">Simpan</button>');
			$("div#MyModal").modal('show');
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	$(document).on('click','#save_add_btn',function(e){
		e.preventDefault();
		var nama_level = $("input#name").val();
		$.ajax({
			method:"POST",
			url:url_ctrl+'act_add',
			cache:false,
			data: {
				name:nama_level
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
				table.row.add({
            		"DT_RowId" : obj.lastid,
				    "0" : obj.lastid,
				    "1" : nama_level
		        }).draw(false);
			}
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	// Edit Button
	$(document).on('click','#edit_btn',function(e){
		e.preventDefault();
		$.ajax({
			method:"GET",
			url:url_ctrl+'edit',
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
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	$(document).on('click','#save_edit_btn',function(e){
		e.preventDefault();
		var new_name = $("input#name").val();
		$.ajax({
			method:"POST",
			url:url_ctrl+'act_edit',
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
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
                notifYesAuto(obj.notif);
                var rowEdit = table.row('tr.actived').data();
				rowEdit[1] = new_name;
				table.row('tr.actived').data(rowEdit).invalidate();
			}
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	// Delete Button
	$(document).on('click','#delete_btn',function(e){
		e.preventDefault();
		rowSelect = table.row('tr.actived').data();
		var id_tbl = rowSelect[0];
		var id_name = rowSelect[1];
		swal({
			title: 'Anda yakin ?',
			text: 'Data '+id_name+' akan di hapus ?',
			type: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, hapus !',
			cancelButtonText: 'Tidak, batalkan !'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					method:"POST",
					url:url_ctrl+'act_del',
					cache:false,
					data: {
						id_tbl:id_tbl,
						id_name:id_name
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
						table.row('tr.actived').remove().draw(false);
					}
				})
				.fail(function(res){
					alert('Error Response !');
					console.log("responseText", res.responseText);
				});
			}
		})
	});

});