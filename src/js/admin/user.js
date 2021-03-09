$(document).ready(function(){

	var url_ctrl = site_url+"admin/user/";

	var table = $('#tabel_custom').DataTable({
        "ajax": url_ctrl+'table',
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

		var lastnum = table.data().count() + 1;
		var fullname = $("input#fullname").val();
		var username = $("input#username").val();
		var level = $("#level option:selected").text();
		var status = $("#status option:selected").text();

		$.ajax({
			method:"POST",
			url:url_ctrl+'act_add',
			cache:false,
			data: {
				fullname:fullname,
	            username:username,
	            password:$("input#password").val(),
	            passconf:$("input#passconf").val(),
	            level:$("select#level").val(),
	            status:$("select#status").val()
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
				    "0" : lastnum,
				    "1" : fullname,
				    "2" : username,
				    "3" : level,
				    "4" : '0000-00-00 00:00:00',
				    "5" : status
		        }).draw(false);
			}
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	// Edit Button
	$(document).on('click','button#edit_btn',function(e){
		e.preventDefault();
	  	$.ajax({
			method:"GET",
			url:url_ctrl+'edit',
			cache:false,
			data:{id_user:$(this).attr('data-id')}
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
		var fullname = $("input#fullname").val();
		var username = $("input#username").val();
		var level = $("#level option:selected").text();
		var status = $("#status option:selected").text();

		$.ajax({
			method:"POST",
			url:url_ctrl+'act_edit',
			data: {
				id_user:$("input#id_user").val(),
				fullname:fullname,
	            username:username,
	            username_old:$("input#username_old").val(),
	            level:$("select#level").val(),
	            status:$("select#status").val()
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
                var temp = table.row('tr.actived').data(); temp[1] = fullname;
				temp[2] = username;
				temp[3] = level;
				temp[5] = status;
				table.row('tr.actived').data(temp).invalidate();
                //reload tanpa pindah pagination
                //table.ajax.reload(null, false);
			}
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	// Delete Button
	$(document).on('click','button#delete_btn',function(e){
		e.preventDefault();
		var id = $(this).attr('data-id');
		var rowData = table.row('tr.actived').data();
		var fullname = rowData['1'];
		swal({
			title: 'Anda yakin ?',
			text: 'User data '+fullname+' akan dihapus ?',
			type: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, hapus !',
			cancelButtonText: 'Tidak, batalkan !'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					method:"POST",
					url:url_ctrl+'act_del',
					data: {
						id_user:id,
						fullname:fullname
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

	// Reset Btn
	$(document).on('click','#reset_btn',function(e){
		e.preventDefault();
		$.ajax({
			method:"GET",
			url:url_ctrl+'reset',
			cache:false,
			data:{id_user:$(this).attr('data-id')}
		})
		.done(function(view) {
			$('#MyModalTitle').html('<b>Reset Password</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalContent").html(view);
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_reset_btn">Reset</button>');
			$("div#MyModal").modal('show');
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	$(document).on('click','#save_reset_btn',function(e){
		e.preventDefault();
		$.ajax({
			method:"POST",
			url:url_ctrl+'act_reset',
			data: {
				id_user:$("input#id_user").val(),
				fullname:$("input#fullname").val(),
	            password:$("input#password").val(),
	            passconf:$("input#passconf").val()
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
			}
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

});