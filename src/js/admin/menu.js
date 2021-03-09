$(document).ready(function(){

	var url_ctrl = site_url+"admin/menu/";

	var cat_menu = $('select#cat_menu').val();
	var table = $('#tabel_custom').DataTable({
        "ajax": url_ctrl+'table/'+cat_menu,
        "deferRender": true
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

	$(document).on('change','select#cat_menu',function(){
		var cat_menu = $(this).val();
		table.ajax.url(url_ctrl+'table/'+cat_menu).load();
	});

	// Add Button
	$(document).on('click','#add_btn',function(e){
		e.preventDefault();
		var akses = $('select#cat_menu').val();
		$.ajax({
			method:"GET",
			cache:false,
			url:url_ctrl+'add/'+akses
		})
		.done(function(view) {
			$('#MyModalTitle').html('<b>Tambah</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalContent").html(view);
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_add_btn">Simpan</button>');
			$("div#MyModal").modal('show');
			$(".multi-select").chosen();

			iconset();
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
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
			url:url_ctrl+'act_add',
			cache:false,
			data: {
	            position:$("input#position").val(),
	            level:JSON.stringify(selectedlevel),
	            icon:$("#icon").val(),
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
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAuto(obj.notif);
				table.ajax.url(url_ctrl+'table/'+$('select#cat_menu').val()).load();
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
		var akses = $('select#cat_menu').val();
		$.ajax({
			method:"GET",
			url:url_ctrl+'edit/'+akses,
			cache:false,
			data:{id_tbl:$(this).attr('data-id')}
		})
		.done(function(view) {
			$('#MyModalTitle').html('<b>Ubah</b>');
			$('div.modal-dialog').addClass('modal-sm');
			$("div#MyModalContent").html(view);
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_edit_btn">Ubah</button>');
			$("div#MyModal").modal('show');
			$(".multi-select").chosen();

			iconset( $("#icon").val() );
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
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
			url:url_ctrl+'act_edit',
			cache:false,
			data: {
				id_tbl:$("input#id_menu").val(),
				position:$("input#position").val(),
	            level:JSON.stringify(selectedlevel),
	            icon:$("#icon").val(),
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
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
                notifYesAuto(obj.notif);
                table.ajax.url(url_ctrl+'table/'+$('select#cat_menu').val()).load();
			}
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	$(document).on('click','#delete_btn',function(e){
		e.preventDefault();
		var id_tbl = $(this).attr('data-id');
		var rowData = table.row('tr.actived').data();
		var name = rowData['2'];
		swal({
			title: 'Anda yakin ?',
			text: 'Menu '+name+' akan di hapus ?',
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
						table.ajax.url(url_ctrl+'table/'+$('select#cat_menu').val()).load();
					}
				})
				.fail(function(res){
					alert('Error Response !');
					console.log("responseText", res.responseText);
				});
			}
		});
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

});

// Setting Icon Picker
function iconset(icon = 'empty'){
	$('#target').iconpicker({
	    align: 'center', // Only in div tag
	    arrowClass: 'btn btn-default',
	    arrowPrevIconClass: 'fa fa-angle-left',
	    arrowNextIconClass: 'fa fa-angle-right',
	    cols: 6,
	    footer: true,
	    header: true,
	    icon: icon,
	    iconset: 'fontawesome',
	    labelHeader: '{0} of {1} pages',
	    labelFooter: '{0} - {1} of {2} icons',
	    placement: 'bottom', // Only in button tag
	    rows: 4,
	    search: true,
	    searchText: 'Search',
	    selectedClass: 'btn-default',
	    unselectedClass: ''
	}); 

	$('#target').on('change', function(e) {
	    $("#icon").val(e.icon);  
	});
}
