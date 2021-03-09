$(document).ready(function(){

	var url_ctrl        = site_url+"apps/arsip/";
    var kate            = $("#id_kategori").val();
    var perusahaan      = $("#id_perusahaan").val();

    var table = $('#tabel_custom').DataTable({
        "ajax": url_ctrl+"table"+"/"+kate+"/"+perusahaan,
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true, 
        fixedColumns:   { leftColumns: 4},
        "deferRender": true,
        "order": [["0", "desc"]],
        "columnDefs":[{"orderable":false,"targets":[2,3,6,7,8,10,11,12,13]}]
    });

    $(document).on('change','select.showDropdown',function(){
        var peru = $('#id_perusahaan').val();
        var kate = $('#id_kategori').val();
        // console.log(kate);
        // console.log(peru);
        table.ajax.url(url_ctrl+"table"+"/"+peru+"/"+kate).load();
    });

    // https://stackoverflow.com/questions/43064023/multiple-child-selector-in-on-javascript

    // Add Button
    $(document).on('click','#add_btn',function(e){
        e.preventDefault();
        var peru =  $("select#id_perusahaan").val();
        var kate =  $("select#id_kategori").val();
        if (peru == 0 || kate == 0 ) {
            swal("Perhatian","Silahkan Pilih Perusahaan dan Kategori","warning");
            return false;
        }

        loadingShow();
        $.ajax({
            method:"GET",
            cache:false,
            url:url_ctrl+'add'
        })
        .done(function(view) {
            $('#MyModalTitle').html('<b>Tambah Data Arsip</b>');
            $('div.modal-dialog').addClass('modal-md');
            $("div#MyModalContent").html(view);
            $("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="save_add_btn">Simpan</button>');
            $("div#MyModal").modal('show');

            var dateToday = new Date();
            $(".date").datetimepicker({
                format: 'Y-m-d',
                scrollInput: false,
                changeMonth : true,
                changeYear : true, 
                timepicker: false, 
            }); 

        })
        .fail(function(res){
            alert('Error Response !');
            console.log("responseText", res.responseText);
        });
    });


    $(document).on('click','#save_add_btn',function(e){
        e.preventDefault();

        var lastnum             = table.data().count() + 1;
        var dokumen             = $("input#dokumen").val();
        var no_dok              = $("input#no_dok").val();
        var disahkan_oleh       = $("input#disahkan_oleh").val();
        var versions            = $("input#versions").val();
        var indonesia           = $("input#indonesia").val();
        var site                = $("input#site").val();
        var issued_date         = $("input#issued_date").val();
        var expired             = $("input#expired").val();
        var keterangan          = $("textarea#keterangan").val();
        var status              = $("input#status").val();
        var id_kategori         = $("select#id_kategori option:selected").val();
        var id_perusahaan       = $("select#id_perusahaan option:selected").val();

        // console.log(id_perusahaan);
        // return false;

        $.ajax({
            method:"POST",
            url:url_ctrl+'act_add',
            cache:false,
            data: {
                dokumen:dokumen,
                no_dok:no_dok,
                disahkan_oleh:disahkan_oleh,
                versions:versions,
                indonesia:indonesia,
                site:site,
                issued_date:issued_date,
                expired:expired,
                keterangan:keterangan,
                status:status,
                id_kategori:id_kategori,
                id_perusahaan:id_perusahaan
            }
        })

        .done(function(result){
            loadingHide(); 
            var obj = jQuery.parseJSON(result);
            if(obj.status == 'failed'){
                swal("Perhatian",obj.notif,"warning");
            }else if(obj.status == 'success'){
                notifYesAuto(obj.notif);
                refresh();
            }
        })
        .fail(function(res){
            alert('Error Response !');
            console.log("responseText", res.responseText);
        });
    });


    //EDIT
    $(document).on('click', '.btn_edit', function(i){
        i.preventDefault();
        var id_arsip =  $(this).attr('data-id');
        // console.log(id_arsip);

        loadingShow();
        $.ajax({
            method:"POST",
            cache:false,
            data:{id_arsip:id_arsip},
            url:url_ctrl+'edit'
        })
        .done(function(view){
            loadingHide();
            $('#MyModalTitle').html('<b>Edit Data Arsip</b>');
            $("div#MyModalContent").html(view);
            $('div.modal-dialog').addClass('modal-md');
            $('div#MyModal').modal({backdrop:'static', keyboard:false})
            $("div#MyModal").modal('show');
            $("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="btn_update" data-id="'+id_arsip+'">Update</button>');

            var dateToday = new Date();
            $(".date").datetimepicker({
                format: 'Y-m-d',
                scrollInput: false,
                changeMonth : true,
                changeYear : true, 
                timepicker: false, 
            }); 
        })
        .fail(function(res){
            loadingHide();
            alert('Error Response !');
            console.log("responseText", res.responseText);
        });
    });

    $(document).on('click', '#btn_update', function(i){
        i.preventDefault();
        var id_arsip            = $(this).attr('data-id');
        var dokumen             = $("input#dokumen").val();
        var no_dok              = $("input#no_dok").val();
        var disahkan_oleh       = $("input#disahkan_oleh").val();
        var versions            = $("input#versions").val();
        var indonesia           = $("input#indonesia").val();
        var site                = $("input#site").val();
        var issued_date         = $("input#issued_date").val();
        var expired             = $("input#expired").val();
        var keterangan          = $("textarea#keterangan").val();
        var status              = $("input#status").val();
        var id_kategori         = $("input#id_kategori").val();
        var id_perusahaan       = $("input#id_perusahaan").val();

        loadingShow();
        $.ajax({
            method:"POST",
            url:url_ctrl+'act_edit',
            cache:false,
            data: {
                id_arsip:id_arsip,
                dokumen:dokumen,
                no_dok:no_dok,
                disahkan_oleh:disahkan_oleh,
                versions:versions,
                indonesia:indonesia,
                site:site,
                issued_date:issued_date,
                expired:expired,
                keterangan:keterangan,
                status:status,
                id_kategori:id_kategori,
                id_perusahaan:id_perusahaan
            }
        })

        .done(function(result){
            loadingHide(); 
            var obj = jQuery.parseJSON(result);
            if(obj.status == 'failed'){
                swal("Perhatian",obj.notif,"warning");
            }else if(obj.status == 'success'){
                notifYesAuto(obj.notif);
                refresh();
            }  
        })
        
        .fail(function(res){
            loadingHide();
            alert('Error Response !');
            console.log("responseText", res.responseText);
        });
    });

    //EDIT
    $(document).on('click', 'a.btn.btn_edit_status', function(i){
        i.preventDefault();
        var id_arsip =  $(this).attr('data-id');
        // console.log(id_arsip);

        loadingShow();
        $.ajax({
            method:"POST",
            cache:false,
            data:{id_arsip:id_arsip},
            url:url_ctrl+'edit_status'
        })
        .done(function(view){
            loadingHide();
            $('#MyModalTitle').html('<b>Edit Data Arsip</b>');
            $("div#MyModalContent").html(view);
            $('div.modal-dialog').addClass('modal-md');
            $('div#MyModal').modal({backdrop:'static', keyboard:false})
            $("div#MyModal").modal('show');
            $("div#MyModalFooter").html('<button type="submit" class="btn btn-default center-block" id="btn_update" data-id="'+id_arsip+'">Update</button>');

        })
        .fail(function(res){
            loadingHide();
            alert('Error Response !');
            console.log("responseText", res.responseText);
        });
    });

    // DELETE
    $(document).on('click','.aksdalsd',function(e){  
        var id_arsip = $(this).attr('data-id'); 
        // console.log(id_arsip);
        swal({
          title: 'Perhatian',
          text: "Yakin akan menghapus data ini",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#28a745' , 
          cancelButtonText: 'Batal' ,
          confirmButtonText: 'Ya, hapus',
           reverseButtons: true
        }).then((result) => {
          if (result.value) {
                $.ajax({
                    method:"POST",
                    cache:false,
                    url:url_ctrl+'act_delete',
                    data:{  
                        id_arsip:id_arsip,
                    }
                })
                .done(function(result) {
                    var obj = jQuery.parseJSON(result);
                    notifYesAuto(obj.notif);
                    refresh();
                })
                .fail(function() {
                    alert("Error");
                    console.log("responseText", res.responseText);
                }); 
            }
        }); 
    });

    // Delete Button
    $(document).on('click','a.btn.btn_delete',function(e){
        e.preventDefault();
        var id_arsip = $(this).attr('data-id');
        console.log(id_arsip);
        swal({
            title: 'Anda yakin ?',
            text: 'Yakin akan menghapus data ini ?',
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#28a745' , 
            cancelButtonText: 'Batal' ,
            confirmButtonText: 'Ya, hapus',
            reverseButtons: true
        }).then(function () {
            $.post(url_ctrl+'act_delete',{
                id_arsip:id_arsip
            })
            .done(function(result) {
                var obj = jQuery.parseJSON(result);
                if(obj.status == 1){
                    notifNoHtml(obj.notif);
                }
                if(obj.status == 2){
                    notifYesAutoHtml(obj.notif);
                    table.ajax.reload(null, false);
                }
            })
            .fail(function(res) {
                alert("Error");
                console.log("Error", res.responseText);
            });
        },function(dismiss) {
            if (dismiss === 'cancel') {
                $("div#MyModal").modal('hide');
                notifCancleAuto('Proses hapus di batalkan.');
            }
        })
    });

    // Select Row Table
    // $('#table_custom tbody').on('click', 'tr', function(e){
    //     e.preventDefault();
    //     if($(this).hasClass('active')){
    //         $(this).removeClass('active');
    //         $(this).addClass('active');
    //     }else{
    //         table.$('tr.active').removeClass('active');
    //         $(this).addClass('active');
    //     }
    //     //rowIndex = table.row(this).index();
    //     rowId = table.row(this).id();
    //     leftCss = e.pageX-50;
    //     $('#popup_menu').css({left:leftCss+"px",top:e.pageY+"px"}).show("fast", function(){
    //         $("button#btn_edit_status").attr('data-id', rowId);
    //     });
    // });

    // $(document).on('click', function(e){
    //     if(e.target.nodeName !== "TD"){
    //         $('#popup_menu').hide();
    //         $('#popup_menu').removeAttr('style');
    //     }
    // });

    // GLOBAL
    function loadingShow() {
        $('#loading').show();
    }

    function loadingHide() {
        $('#loading').hide();
    }

    function refresh() {
        table.ajax.reload();
        $("div#MyModal").modal('hide'); 
    }

});	