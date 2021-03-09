$(document).ready(function(){

	var url_ctrl = site_url+"admin/profil/";
	
	var url_crop_pic		= site_url+"admin/profil/crop_pic";
	var url_act_crop_pic	= site_url+"admin/profil/act_crop_pic";

	$.fn.LoadContent = function(){this.load(url_ctrl+'edit');}

	$("div#load_tabel").LoadContent();

	// Act Edit Username
	$(document).on('click','#save_edit_btn',function(e){
		e.preventDefault();
		$.ajax({
				method:"POST",
				url:url_ctrl+'act_edit',
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
	                notifNo(obj.notif);
				}
				if(obj.status == 2){
	                $("div#MyModal").modal('hide');
	                notifYesAuto(obj.notif);
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
				url:url_ctrl+'upload_ava'
			})
			.done(function(view) {
				$('#MyModalTitle').html('<i class="fa fa-image"></i> Best Ratio : 225px x 225px');
				$("div#MyModalContent").html(view);
		    	$("#UploadForm").dropzone({ 
                    url: url_ctrl+'act_upload_ava',
                    maxFiles: 1,
                    maxFilesize: 5,
                    acceptedFiles: 'image/*',
					init: function() {
				        this.on("success", function() {
				            $("div#load_tabel").LoadContent();
				            $('div#MyModal').modal('hide');
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
	                notifNo(obj.notif);
				}
				if(obj.status == 2){
	                $("div#MyModal").modal('hide');
	                notifYesAuto(obj.notif);
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
				url:url_ctrl+'edit_pass',
				cache:false
			})
			.done(function(view) {
				$('#MyModalTitle').html('<b>Ubah Password</b>');
				$('div.modal-dialog').addClass('modal-sm');
				$("div#MyModalContent").html(view);
				$("div#MyModalFooter").html('<button type="submit" class="btn btn-default btn-block" id="save_edit_password">Simpan</button>');
				$("div#MyModal").modal('show');
			})
			.fail(function(res){
				alert('Error Response !');
				console.log("responseText", res.responseText);
			});
	});

	// Act Edit Password
	$(document).on('click','#save_edit_password',function(e){
		e.preventDefault();
		$.ajax({
				method:"POST",
				url:url_ctrl+'act_edit_pass',
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