$(document).ready(function(){
	var url_ctrl = site_url+"admin/api_type/";
    
    var url_setup         = site_url+"admin/api_type/api_setup";
    var url_act_setup     = site_url+"admin/api_type/act_api_setup";

    var table = $('#tbl_arsip').DataTable({
        "ajax": url_ctrl+'table',
        "deferRender": true,
        "order": [["0", "desc"]]
    });

    $(document).on('click','button#add_btn',function(e){
        e.preventDefault();
        $.get(url_setup)
        .done(function(view) {
            $('#MyModalTitle').html('<b>Api Type</b>');
            //$('div.modal-dialog').addClass('modal-sm');
            $("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_apisetup_btn">Activate</button>');
            $("div#MyModalContent").html(view);
            $("div#MyModal").modal('show');
        })
        .fail(function() {
            alert("Error");
        });
    });

    $(document).on('click','button#save_apisetup_btn',function(e){
        e.preventDefault();
        $.post(url_act_setup,{
            id:$("select[name*='id']").val(),
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

});