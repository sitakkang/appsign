$(document).ready(function(){
	var url_ctrl = site_url+"admin/signer/";

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
			$('input.tanggal').datetimepicker({
				timepicker:false,
				format:'Y-m-d',
				scrollMonth:false,
				scrollTime:false,
				scrollInput:false,
			});
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});


	$(document).on('click','#save_add_btn',function(e){
		e.preventDefault();

		var lastnum = table.data().count() + 1;
		var name = $("input#name").val();
		var email_user = $("input#email_user").val();
		var email_digisign = $("input#email_digisign").val();
		var kuser_production = $("input#kuser_production").val();
		var kuser_sandbox = $("input#kuser_sandbox").val();
		var id_ktp = $("input#id_ktp").val();
		var id_npwp = $("input#id_npwp").val();
		var jenis_kelamin = $("select#jenis_kelamin").val();
		var telepon = $("input#telepon").val();
		var alamat = $("textarea#alamat").val();
		var provincy = $("select#provincy").val();
		var kota = $("select#kota").val();
		var kecamatan = $("select#kecamatan").val();
		var desa = $("select#desa").val();
		var kode_pos = $("input#kode_pos").val();
		var tempat_lahir = $("input#tempat_lahir").val();
		var tgl_lahir = $("input#tgl_lahir").val();
		$.ajax({
			method:"POST",
			url:url_ctrl+'act_add',
			cache:false,
			data: {
				name:name,
	            email_user:email_user,
	            id_ktp:id_ktp,
	            id_npwp:id_npwp,
	            jenis_kelamin:jenis_kelamin,
	            telepon:telepon,
	            alamat:alamat,
	            provincy:provincy,
	            kota:kota,
	            kecamatan:kecamatan,
	            desa:desa,
	            kode_pos:kode_pos,
	            tempat_lahir:tempat_lahir,
	            tgl_lahir:tgl_lahir,
	            email_digisign:email_digisign,
	            kuser_sandbox:kuser_sandbox,
	            kuser_production:kuser_production,
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
				    "1" : name,
				    "2" : email_user,
				    "3" : email_digisign,
				    "4" : kuser_production,
				    "5" : kuser_sandbox,
				    
				    
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
			data:{id:$(this).attr('data-id')}
		})
		.done(function(view) {
			$('#MyModalTitle').html('<b>Ubah</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalContent").html(view);
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_edit_btn">Ubah</button>');
			$("div#MyModal").modal('show');
			$('input.tanggal').datetimepicker({
				timepicker:false,
				format:'Y-m-d',
				scrollMonth:false,
				scrollTime:false,
				scrollInput:false,
			});
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

    
	$(document).on('click','#save_edit_btn',function(e){
		e.preventDefault();
		var name = $("input#name").val();
		var email_user = $("input#email_user").val();
		var email_digisign = $("input#email_digisign").val();
		var kuser_production = $("input#kuser_production").val();
		var kuser_sandbox = $("input#kuser_sandbox").val();
		var id_ktp = $("input#id_ktp").val();
		var id_npwp = $("input#id_npwp").val();
		var jenis_kelamin = $("select#jenis_kelamin").val();
		var telepon = $("input#telepon").val();
		var alamat = $("textarea#alamat").val();
		var provincy = $("select#provincy").val();
		var kota = $("select#kota").val();
		var kecamatan = $("select#kecamatan").val();
		var desa = $("select#desa").val();
		var kode_pos = $("input#kode_pos").val();
		var tempat_lahir = $("input#tempat_lahir").val();
		var tgl_lahir = $("input#tgl_lahir").val();
		$.ajax({
			method:"POST",
			url:url_ctrl+'act_edit',
			data: {
				id:$("input#id").val(),
				name:name,
	            email_user:email_user,
	            id_ktp:id_ktp,
	            id_npwp:id_npwp,
	            jenis_kelamin:jenis_kelamin,
	            telepon:telepon,
	            alamat:alamat,
	            provincy:provincy,
	            kota:kota,
	            kecamatan:kecamatan,
	            desa:desa,
	            kode_pos:kode_pos,
	            tempat_lahir:tempat_lahir,
	            tgl_lahir:tgl_lahir,
	            email_digisign:email_digisign,
	            kuser_sandbox:kuser_sandbox,
	            kuser_production:kuser_production,
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
                var temp = table.row('tr.actived').data(); 
                temp[1] = name;
				temp[2] = email_user;
				temp[3] = email_digisign;
			    temp[4] = kuser_production;
			    temp[5] = kuser_sandbox;
				table.row('tr.actived').data(temp).invalidate();
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
		var name = rowData['2'];
		swal({
			title: 'Anda yakin ?',
			text: 'Signer '+name+' akan dihapus ?',
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
						id:id,
						name:name
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

	$(document).on('change','#provincy',function(e){
		e.preventDefault();
		var id = $("select[id*='provincy']").val();
	  	$.ajax({
			method:"GET",
			url:url_ctrl+'getKota',
			cache:false,
			data:{id:id}
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 2){
                $('#kota').html(obj.html);
                $('#kecamatan').html(obj.kecamatan_html);
                $('#desa').html(obj.desa_html);
			}
			
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	$(document).on('change','#kota',function(e){
		e.preventDefault();
		var id = $("select[id*='kota']").val();
	  	$.ajax({
			method:"GET",
			url:url_ctrl+'getKecamatan',
			cache:false,
			data:{id:id}
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 2){
                $('#kecamatan').html(obj.html);
                $('#desa').html(obj.desa_html);
			}
			
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	$(document).on('change','#kecamatan',function(e){
		e.preventDefault();
		var id = $("select[id*='kecamatan']").val();
	  	$.ajax({
			method:"GET",
			url:url_ctrl+'getDesa',
			cache:false,
			data:{id:id}
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 2){
                $('#desa').html(obj.html);
			}
			
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

});