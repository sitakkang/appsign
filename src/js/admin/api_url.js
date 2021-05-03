$(document).ready(function(){
	var url_ctrl = site_url+"admin/api_url/";
    var url_add         = site_url+"admin/api_url/add";
    var url_edit        = site_url+"admin/api_url/edit/";
    var url_act_add     = site_url+"admin/api_url/act_add";
    var url_act_edit    = site_url+"admin/api_url/act_edit";
    var url_act_del     = site_url+"admin/api_url/act_del";

	var table = $('#tbl_arsip').DataTable({
        "ajax": url_ctrl+'table',
        "deferRender": true,
        "order": [["0", "desc"]],


        "scrollX" : true,
        "scrollCollapse" : true,
        "fixedColumns" :   true,
        "fixedColumns" :   {
            "rightColumns" : 1
        },
        "columnDefs": [
            { "orderable": false, "targets": [1,2] },
            { "className": "nowrap", "targets": [ 1,2,3 ] }
        ]
    });

    $(document).on('click','button#add_btn',function(e){
        e.preventDefault();
        $.get(url_add)
        .done(function(view) {
            $('#MyModalTitle').html('<b>Api URL</b>');
            $('div.modal-dialog').addClass('modal-sm');
            $("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_add_btn">Simpan</button>');
            $("div#MyModalContent").html(view);
            $("div#MyModal").modal('show');
            $('input.tanggal').datetimepicker({
                timepicker:false,
                format:'Y-m-d',
                scrollMonth:false,
                scrollTime:false,
                scrollInput:false,
            });
        })
        .fail(function() {
            alert("Error");
        });
    });

    $(document).on('click','button#save_add_btn',function(e){
        e.preventDefault();
        $.post(url_act_add,{
            api_key:$("input[name*='api_key']").val(),
            url:$("input[name*='url']").val(),
            type:$("select[name*='type']").val(),
        })
        .done(function(result) {
            var obj = jQuery.parseJSON(result);
            if(obj.status == 1){
                notifNo(obj.notif);
            }
            if(obj.status == 2){
                $("div#MyModal").modal('hide');
                notifYesAuto(obj.notif);
                table.ajax.reload(null, false);
            }
        })
        .fail(function(res) {
            alert("Error");
            console.log("Error", res.responseText);

        });
    });

    // Edit
    $(document).on('click','a.edit_act_btn',function(e){
        e.preventDefault();
        var id_surat = $(this).attr('data-id');
        $.get(url_edit+id_surat)
        .done(function(view) {
            $('#MyModalTitle').html('<b>Edit</b>');
            $("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_edit_btn">Simpan</button>');
            $("div#MyModalContent").html(view);
            $("div#MyModal").modal('show');
            $('input.tanggal').datetimepicker({
                timepicker:false,
                format:'Y-m-d',
                scrollMonth:false,
                scrollTime:false,
                scrollInput:false,
            });
        })
        .fail(function(res) {
            alert("Error");
            console.log("Error", res.responseText);
        });
    });

    $(document).on('click','button#save_edit_btn',function(e){
        e.preventDefault();
        $.post(url_act_edit,{
            id:$("input[name*='id']").val(),
            api_key:$("input[name*='api_key']").val(),
            url:$("input[name*='url']").val(),
            type:$("select[name*='type']").val(),
        })
        .done(function(result) {
            var obj = jQuery.parseJSON(result);
            if(obj.status == 1){
                notifNo(obj.notif);
            }
            if(obj.status == 2){
                $("div#MyModal").modal('hide');
                notifYesAuto(obj.notif);
                table.ajax.reload(null, false);
            }
        })
        .fail(function(res) {
            alert("Error");
            console.log("Error", res.responseText);

        });
    });

    // $(document).on('click','a.delete_act_btn',function(e){
    //     e.preventDefault();
    //     var id = $(this).attr('data-id');
    //     var api_key = $(this).attr('data-key_');
    //     var type = $(this).attr('data-type');
    //     swal({
    //         title: 'Anda yakin ?',
    //         text: 'Data API URL '+api_key+' akan di hapus ?',
    //         type: 'question',
    //         showCancelButton: true,
    //         confirmButtonText: 'Ya, hapus !',
    //         cancelButtonText: 'Tidak, batalkan !',
    //         confirmButtonClass: 'btn btn-danger',
    //         cancelButtonClass: 'btn btn-primary',
    //         buttonsStyling: false
    //     }).then(function () {
    //         $.post(url_act_del,{
    //             id:id,api_key:api_key,type:type
    //         })
    //         .done(function(result) {
    //             var obj = jQuery.parseJSON(result);
    //             if(obj.status == 1){
    //                 notifNo(obj.notif);
    //             }
    //             if(obj.status == 2){
    //                 notifYesAuto(obj.notif);
    //                 table.ajax.reload(null, false);
    //             }
    //         })
    //         .fail(function(res) {
    //             alert("Error");
    //             console.log("Error", res.responseText);
    //         });
    //     },function(dismiss) {
    //         if (dismiss === 'cancel') {
    //             $("div#MyModal").modal('hide');
    //             notifCancleAuto('Proses hapus di batalkan.');
    //         }
    //     })
    // });
    // Delete Button
    // Delete Button
    $(document).on('click','.delete_act_btn',function(e){
        e.preventDefault();
        
        var id = $(this).attr('data-id');
        var api_key = $(this).attr('data-key_');
        var type = $(this).attr('data-type');
        swal({
            title: 'Anda yakin ?',
             text: 'Data dengan api_key '+api_key+' dengan type ' + type + ' akan di hapus ?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus !',
            cancelButtonText: 'Tidak, batalkan !'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    method:"POST",
                    url:url_act_del,
                    cache:false,
                    data: {
                        id:id,
                        api_key:api_key,
                        type:type
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
                        table.ajax.reload(null, false);
                        // table.ajax.url(url_ctrl+'table/'+$('select#cat_menu').val()).load();
                    }
                })
                .fail(function(res){
                    alert('Error Response !');
                    console.log("responseText", res.responseText);
                });
            }
        });
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
        $("div#MyModalFooter").empty();
    });
});