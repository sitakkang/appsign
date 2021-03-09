$(document).ready(function(){
	
	var url_tbl 		= site_url+"admin/menu/table/";
	var url_add 		= site_url+"admin/menu/add/";
	var url_edit 		= site_url+"admin/menu/edit/";
	var url_act_add 	= site_url+"admin/menu/act_add";
	var url_act_edit 	= site_url+"admin/menu/act_edit";
	var url_act_del 	= site_url+"admin/menu/act_del";

	$('[data-toggle="tooltip"]').tooltip();

	var cat_menu = $('select#cat_menu').val();
	var table = $('#tabel_custom').DataTable({
        "ajax": url_tbl+cat_menu,
        "deferRender": true
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

	$(document).on('change','select#cat_menu',function(){
		var cat_menu = $(this).val();
		table.ajax.url(url_tbl+cat_menu).load();
	});

	// Add Button
	$(document).on('click','#add_btn',function(e){
		e.preventDefault();
		var akses = $('select#cat_menu').val();
		$.ajax({
				method:"GET",
				cache:false,
				url:url_add+akses
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
		if($('select#level :selected').length > 0){
            var selectedlevel = [];
            $('select#level :selected').each(function(i, selected){
                selectedlevel[i] = $(selected).val();
            });
        }else{ selectedlevel = ''; }
		$.ajax({
				method:"POST",
				url:url_act_add,
				cache:false,
				data: {
		            position:$("input#position").val(),
		            level:JSON.stringify(selectedlevel),
		            icon:$("select#icon").val(),
		            name:$("input#name").val(),
					akses:$("input#akses").val(),
		            sub:$("select#sub").val(),
		            link:$("input#link").val(),
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
					table.ajax.url(url_tbl+$('select#cat_menu').val()).load();
				}
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Edit Button
	$(document).on('click','#edit_btn',function(e){
		e.preventDefault();
		var akses = $('select#cat_menu').val();
		$.ajax({
				method:"GET",
				url:url_edit+akses,
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
		if($('select#level :selected').length > 0){
            var selectedlevel = [];
            $('select#level :selected').each(function(i, selected){
                selectedlevel[i] = $(selected).val();
            });
        }else{ selectedlevel = ''; }
		$.ajax({
				method:"POST",
				url:url_act_edit,
				cache:false,
				data: {
					id_tbl:$("input#id_menu").val(),
					position:$("input#position").val(),
		            level:JSON.stringify(selectedlevel),
		            icon:$("select#icon").val(),
		            name:$("input#name").val(),
		            name_old:$("input#name_old").val(),
					akses:$("input#akses").val(),
		            sub:$("select#sub").val(),
		            link:$("input#link").val(),
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
	                table.ajax.url(url_tbl+$('select#cat_menu').val()).load();
				}
			})
			.fail(function() {
				alert("Error");
			});
	});

	$(document).on('click','#delete_btn',function(e){
		e.preventDefault();
		var id_tbl = $(this).attr('data-id');
		var rowData = table.row('tr.active').data();
		var name = rowData['2'];
		swal({
			title: 'Anda yakin ?',
			text: 'Menu '+name+' akan di hapus ?',
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
						name:name
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
						table.ajax.url(url_tbl+$('select#cat_menu').val()).load();
					}
				})
				.fail(function() {
					alert("Error");
				});
		},function(dismiss) {
			if (dismiss === 'cancel') {
				$("div#MyModal").modal('hide');
				notifCancleAuto('Proses hapus dibatalkan !');
			}
		})
	});

	// Select Link
	$(document).on('change','select#sub',function(e){
		e.preventDefault();
		if($(this).val() == 1){
			$('#link_display').show();
		}else{
			$('#link_display').hide();
		}
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
	});

});