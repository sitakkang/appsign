function notifNoAuto(data){
	swal({
		type: 'error',
		title: 'Warning !',
		html: data,
		showConfirmButton: false,
		timer: 2000
	})
};

function notifYesAuto(data){
	swal({
		type: 'success',
		title: 'Success',
		html: data,
		showConfirmButton: false,
		timer: 2000
	})
};

function notifNo(data){
	swal({
		type: 'error',
		title: 'Warning !',
		html: data
	})
};

function notifYes(data){
	swal({
		type: 'success',
		title: 'Success',
		html: data
	})
};

function notifCancle(data){
	swal({
		type: 'warning',
		title: 'Canceled',
		text: data,
		showConfirmButton: false,
		timer: 2000
	})
};

function loadingShow() {
	$('#loading').show();
}

function loadingHide() {
	$('#loading').hide();
}

$(document).ready(function(){
	// Setting Modal and Sweet Alert 2
	$("div#MyModal").on('shown.bs.modal',function(e){
		e.preventDefault();
		$('body').removeAttr('style');
	});
	$("div#MyModal").on('hidden.bs.modal',function(e){
		e.preventDefault();
		$('h5#MyModalTitle').empty();
		$("div#MyModalContent").empty();
		$("div#MyModalFooter").empty();
		$('div.modal-dialog').removeClass('modal-lg');
		$('div.modal-dialog').removeClass('modal-sm');
	});
});