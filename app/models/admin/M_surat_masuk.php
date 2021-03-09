<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class M_surat_masuk extends CI_Model {

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
						$view .= '<a class="btn btn-primary btn_preview_mail" data-tipe="2" data-url="'.$key.'" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-inbox"></i></a> ';
					}
				}else{
					$view .= '';
				}
			}
			return $view;
		}
	}

	function masuk_act_btn($id, $no_surat)
	{
		return '<a class="btn btn-info upload_act_btn" data-id="'.$id.'" title="Upload" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-upload"></i></a> <a class="btn btn-primary send_act_btn" data-id="'.$id.'" title="Kirim" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fas fa-envelope-square"></i></a> <a class="btn btn-warning edit_act_btn" data-id="'.$id.'" title="Edit" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fas fa-edit"></i></a> <a class="btn btn-danger delete_act_btn" data-id="'.$id.'" data-surat="'.$no_surat.'" title="Hapus" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-times"></i></a>';
	}

	function label_status_masuk($data, $id)
	{
		switch ($data) {
			case 0:
				return '<span class="label popup label-danger" data-id="'.$id.'">Belum dikirim</span>';
				break;
			case 1:
				return '<span class="label popup label-primary" data-id="'.$id.'">Diterima</span>';
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

	function select_status($data)
    {
		$arr = array("Belum Dikirim", "Diterima", "Dalam Proses", "Selesai");
        for($x = 0; $x < 5; $x++){
			if($x == $data){
				echo '<option value="'.$x.'" selected>'.$arr[$x].'</option>';
			}else{
				echo '<option value="'.$x.'">'.$arr[$x].'</option>';
			}
		}
	}

	function penerima_surat($data)
	{
		if(empty($data)){
			return '';
		}else{
			$string = str_replace('"', '', $data);
			$exp = explode(",", $string);
	        $arrlength = count($exp);
	        $val = '';
	        for($x = 0; $x < $arrlength; $x++) {
				$nama = $this->nama_penerima($exp[$x]);
				if(isset($nama)){
					$val .= ' [ '.$nama.' ] ';
				}
	            
	        }
	        return $val;
		}
	}

	function select_tujuan($data)
    {
        $query = $this->db->query('SELECT id_user, fullname FROM conf_users WHERE level > 0 ORDER BY id_user');
		if(empty($data)){
			foreach($query->result() as $id) {
				echo '<option value="'.$id->id_user.'">'.$id->fullname.'</option>';
			}
		}else{
			foreach($query->result() as $id) {
				if(strstr($data, $id->id_user) != FALSE){
					echo '<option value="'.$id->id_user.'" selected="selected">'.$id->fullname.'</option>';
				}else{
					echo '<option value="'.$id->id_user.'">'.$id->fullname.'</option>';
				}
			}
		}
	}

	function jenis_surat($data)
	{
		$opt_srt = array(
			"Undangan",
			"Pemberitahuan",
			"Permohonan",
			"Laporan",
			"Usulan",
			"Internal",
			"Perjanjian",
			"Informasi",
			"Sponsor",
			"Lain-Lain"
		);
		foreach($opt_srt as $val) {
			if($data == $val){
				echo '<option value="'.$val.'" selected>'.$val.'</option>';
			}else{
				echo '<option value="'.$val.'">'.$val.'</option>';
			}
			
		}
	}

	function bentuk_surat($data)
	{
		$opt_srt = array(
			"Hardcopy",
			"Email",
			"Fax"
		);
		foreach($opt_srt as $val) {
			if($data == $val){
				echo '<option value="'.$val.'" selected>'.$val.'</option>';
			}else{
				echo '<option value="'.$val.'">'.$val.'</option>';
			}
			
		}
	}
}