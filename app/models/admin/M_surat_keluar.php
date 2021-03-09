<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class M_surat_keluar extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    function attachment($data)
	{
		if(is_array($data)){
			$view = '';
			foreach ($data as $key) {
				if(isset($key)){
					$char = substr($key, -3);
					if($char == 'pdf'){
						$view .= '<a class="btn btn-primary btn_preview_mail" data-tipe="1" data-url="'.$key.'" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-document"></i></a> ';
					}else{
						$view .= '<a class="btn btn-primary btn_preview_mail" data-tipe="2" data-url="'.$key.'" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-envelope-open-text"></i></a> ';
					}
				}else{
					$view .= '';
				}
			}
			return $view;
		}
	}

    function keluar_act_btn($id, $no_surat)
	{
		return '<a class="btn btn-xs btn-info upload_act_btn" data-id="'.$id.'" title="Upload" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-upload"></i></a> <a class="btn btn-xs btn-primary send_act_btn" data-id="'.$id.'" title="Sign" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-check"></i></a> <a class="btn btn-xs btn-warning edit_act_btn" data-id="'.$id.'" title="Edit" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-edit"></i></a> <a class="btn btn-xs btn-danger delete_act_btn" data-id="'.$id.'" data-surat="'.$no_surat.'" title="Hapus" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-times"></i></a>';
	}

	function label_status_keluar($data, $id)
	{
		switch ($data) {
			case 0:
				return '<span class="label popup label-danger" data-id="'.$id.'">Belum dikirim</span>';
				break;
			case 1:
				return '<span class="label popup label-primary" data-id="'.$id.'">Telah dikirim</span>';
				break;
			case 2:
				return '<span class="label popup label-warning" data-id="'.$id.'">Dalam proses</span>';
				break;
			case 3:
				return '<span class="label popup label-success" data-id="'.$id.'">Selesai</span>';
				break;
			default:
				return '<span class="label popup label-primary" data-id="'.$id.'">Undetected</span>';
				break;
		}
	}
}