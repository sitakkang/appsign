$(document).ready(function(){
	
	var url_edit			= site_url+"admin/profil/edit";
	var url_act_edit 		= site_url+"admin/profil/act_edit";
	var url_upload 			= site_url+"admin/profil/upload_ava";
	var url_act_upload		= site_url+"admin/profil/act_upload_ava";
	var url_edit_pass		= site_url+"admin/profil/edit_pass";
	var url_act_edit_pass	= site_url+"admin/profil/act_edit_pass";
	var url_crop_pic		= site_url+"admin/profil/crop_pic";
	var url_act_crop_pic	= site_url+"admin/profil/act_crop_pic";

	$.fn.LoadContent = function(){this.load(url_edit);}

	$("div#load_tabel").LoadContent();

	// Act Edit Username
	$(document).on('click','#save_edit_btn',function(e){
		e.preventDefault();
		$.ajax({
				method:"POST",
				url:url_act_edit,
				cache:false,
				data: {
					id_tbl:$("input#id_user").val(),
		            fullname:$("input#fullname").val(),
		            gender:$("select#gender").val(),
		            username:$("input#username").val(),
		            username_old:$("input#username_old").val()
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
	                setTimeout("location.reload(true);",2300);
				}
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Upload Avatar
	$(document).on('click','#edit_ava',function(e){
		e.preventDefault();
		$.ajax({
				method:"GET",
				cache:false,
				url:url_upload
			})
			.done(function(view) {
				$('h4.modal-title').text('Best Ratio : 225px x 225px');
				$('button.save_modal').attr('id','save_crop_pic').text('Save');
				$("div.modal-body").html(view);
		    	$("#UploadForm").dropzone({ 
                    url: url_act_upload,
                    maxFiles: 1,
                    maxFilesize: 5,
                    acceptedFiles: 'image/*',
					init: function() {
				        this.on("success", function() {
				            $("div#load_tabel").LoadContent();
				     //     	$('div.preview_img').load(url_crop_pic,function(data){
				     //     		var jcrop_api;

							  //   $('#image_crop').Jcrop({
							  //     onChange:   showCoords,
							  //     onSelect:   showCoords,
							  //     onRelease:  clearCoords,
							  //     minSize: [100,100],
							  //     bgColor:    'black',
							  //     keySupport: false,
							  //     bgOpacity:  .5,
							  //     aspectRatio: 1 / 1
							  //   },function(){
							  //     jcrop_api = this;
							  //   });

							  //   $('#coords').on('change','input',function(e){
							  //     var x1 = $('#x1').val(),
							  //         x2 = $('#x2').val(),
							  //         y1 = $('#y1').val(),
							  //         y2 = $('#y2').val();
							  //     jcrop_api.setSelect([x1,y1,x2,y2]);
							  //   });

							  // function showCoords(c)
							  // {
							  //   $('input#x1_img').val(c.x);
							  //   $('input#y1_img').val(c.y);
							  //   $('input#x2_img').val(c.x2);
							  //   $('input#y2_img').val(c.y2);
							  // };

							  // function clearCoords()
							  // {
							  //   $('input#x1_img').val('');
							  //   $('input#y1_img').val('');
							  //   $('input#x2_img').val('');
							  //   $('input#y2_img').val('');
							  // };

				     //     	});
				        });
				    }
                });
                $("div#MyModal").modal('show');
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Act Crop Image
	$(document).on('click','#save_crop_pic',function(e){
		e.preventDefault();
		$.ajax({
				method:"POST",
				url:url_act_crop_pic,
				cache:false,
				data: {
		            x1_img:$('input#x1_img').val(),
				    y1_img:$('input#y1_img').val(),
				    x2_img:$('input#x2_img').val(),
				    y2_img:$('input#y2_img').val(),
				    url_img:$('input#url_img').val()
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
	                $("div#load_tabel").LoadContent();
				}
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Edit Password
	$(document).on('click','#edit_pass',function(e){
		e.preventDefault();
		$.ajax({
				method:"GET",
				url:url_edit_pass,
				cache:false
			})
			.done(function(view) {
				$('#MyModalTitle').html('<b>Ubah Password</b>');
				$('div.modal-dialog').addClass('modal-sm');
				$("div#MyModalContent").html(view);
				$("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_edit_password">Simpan</button>');
				$("div#MyModal").modal('show');
			})
			.fail(function() {
				alert("Error");
			});
	});

	// Act Edit Password
	$(document).on('click','#save_edit_password',function(e){
		e.preventDefault();
		$.ajax({
				method:"POST",
				url:url_act_edit_pass,
				cache:false,
				data: {
		            old_password:$("input#old_pass").val(),
		            new_password:$("input#new_pass").val(),
		            confirm_password:$("input#conf_pass").val()
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
    	$('button.save_modal').removeAttr('id');
    	$("div.modal-body").empty();
	});

});