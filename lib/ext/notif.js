function notifNoAuto(data){
	swal({
		type: 'error',
		title: 'Kesalahan',
		text: data,
		showConfirmButton: false,
		timer: 2000
	}).catch(swal.noop)
};

function notifYesAuto(data){
	swal({
		type: 'success',
		title: 'Sukses',
		text: data,
		showConfirmButton: false,
		timer: 2000
	}).catch(swal.noop)
};

function notifNoAutoHtml(data){
	swal({
		type: 'error',
		title: 'Kesalahan',
		html: data,
		showConfirmButton: false,
		timer: 2000
	}).catch(swal.noop)
};

function notifYesAutoHtml(data){
	swal({
		type: 'success',
		title: 'Sukses',
		html: data,
		showConfirmButton: false,
		timer: 2000
	}).catch(swal.noop)
};

function notifNo(data){
	swal({
		type: 'error',
		title: 'Kesalahan',
		text: data,
		confirmButtonColor: '#d9534f'
	})
};

function notifYes(data){
	swal({
		type: 'success',
		title: 'Sukses',
		text: data,
		confirmButtonColor: '#00796b'
	})
};

function notifNoHtml(data){
	swal({
		type: 'error',
		title: 'Kesalahan',
		html: data,
		confirmButtonColor: '#d9534f'
	})
};

function notifYesHtml(data){
	swal({
		type: 'success',
		title: 'Sukses',
		html: data,
		confirmButtonColor: '#00796b'
	})
};

function notifCancleAuto(data){
	swal({
		type: 'error',
		title: 'Batal',
		text: data,
		showConfirmButton: false,
		timer: 2000
	}).catch(swal.noop)
};