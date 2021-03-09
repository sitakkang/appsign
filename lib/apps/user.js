$(document).ready(function(){
	
	var url_tbl 		= site_url+"apps/user/table";
	var url_add 		= site_url+"apps/user/add";
	var url_edit 		= site_url+"apps/user/edit";
	var url_reset 		= site_url+"apps/user/reset";
	var url_act_add 	= site_url+"apps/user/act_add";
	var url_act_edit 	= site_url+"apps/user/act_edit";
	var url_act_del 	= site_url+"apps/user/act_del";
	var url_act_res 	= site_url+"apps/user/act_reset";

	var table = $('#tabel_custom').DataTable({
        "ajax": url_tbl,
        "deferRender": true,
        "order": [["0", "desc"]],
        "columnDefs": [
            {
                "targets": [ 3 ],
                "visible": false,
                "searchable": false
            },
        ]
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

		var lastnum = table.data().count() + 1;
		var fullname = $("input#fullname").val();
		var username = $("input#username").val();
		var level = 'Personal';
		var status = $("#status option:selected").text();

		$.ajax({
				method:"POST",
				url:url_act_add,
				cache:false,
				data: {
					fullname:fullname,
		            username:username,
		            password:'imip123',
		            passconf:'imip123',
		            level:3,
		            status:$("select#status").val()
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
					    "0" : lastnum,
					    "1" : fullname,
					    "2" : username,
					    "3" : level,
					    "4" : '0000-00-00 00:00:00',
					    "5" : status
			        }).draw(false);
				}
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Edit Button
	$(document).on('click','button#edit_btn',function(e){
		e.preventDefault();
	  	$.ajax({
			method:"GET",
			url:url_edit,
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
		.fail(function(res) {
			alert("Error");
			console.log(res.responseText);
		});
	});

	$(document).on('click','#save_edit_btn',function(e){
		e.preventDefault();
		var fullname = $("input#fullname").val();
		var username = $("input#username").val();
		var level = 'Personal';
		var status = $("#status option:selected").text();

		$.ajax({
				method:"POST",
				url:url_act_edit,
				data: {
					id_user:$("input#id_user").val(),
					fullname:fullname,
		            username:username,
		            username_old:$("input#username_old").val(),
		            level:3,
		            status:$("select#status").val()
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
	                var temp = table.row('tr.active').data();
					temp[1] = fullname;
					temp[2] = username;
					temp[3] = level;
					temp[5] = status;
					table.row('tr.active').data(temp).invalidate();
	                //reload tanpa pindah pagination
	                //table.ajax.reload(null, false);
				}
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Delete Button
	$(document).on('click','button#delete_btn',function(e){
		e.preventDefault();
		var id = $(this).attr('data-id');
		var rowData = table.row('tr.active').data();
		var fullname = rowData['1'];
		swal({
			title: 'Anda yakin ?',
			text: 'User data '+fullname+' akan dihapus ?',
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
					data: {
						id_user:id,
						fullname:fullname
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
				notifCancleAuto('Proses hapus dibatalkan.');
			}
		})
	});

	// Reset Btn
	$(document).on('click','#reset_btn',function(e){
		e.preventDefault();
		$.ajax({
				method:"GET",
				url:url_reset,
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
			.fail(function() {
				alert("Error");
			});
	});

	$(document).on('click','#save_reset_btn',function(e){
		e.preventDefault();
		$.ajax({
				method:"POST",
				url:url_act_res,
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
	                notifNoHtml(obj.notif);
				}
				if(obj.status == 2){
	                $("div#MyModal").modal('hide');
	                notifYesAutoHtml(obj.notif);
				}
			})
			.fail(function() {
				alert("Error");
			});
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