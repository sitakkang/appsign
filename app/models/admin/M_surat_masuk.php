<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class M_surat_masuk extends CI_Model {

    /*
	status Surat
	O = Mail Created
	1 =  Upload Document
	2 = Signature set
	3 = Document Failed
	4 = Document Sent
	5 = Waiting Approval
	6 = Approval Cancel
	7 = Approve Signature
	8 = Document Signed
    */

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
		$return_data='';
		$return_data.='<a class="btn btn-warning edit_act_btn" data-id="'.$id.'" title="Edit" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fas fa-edit"></i></a> <a class="btn btn-danger delete_act_btn" data-id="'.$id.'" data-surat="'.$no_surat.'" title="Hapus" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-times"></i></a> <a class="btn btn-info upload_act_btn" data-id="'.$id.'" title="Upload" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-upload"></i></a>';
		$query = $this->db->query('SELECT approval_status, status FROM app_surat_masuk WHERE id_surat_masuk='.$id.' LIMIT 1');
        $rows = $query->row();
        if($rows->status==1 || $rows->status==2 || $rows->status==4 || $rows->status== 5|| $rows->status==7){
            $return_data.=' <a class="btn btn-danger posisi_act_btn" data-id="'.$id.'" data-surat="'.$no_surat.'" title="Posisi" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-file-powerpoint"></i></a>';
        }
        if($rows->status==2 ||$rows->status==4 || $rows->status==5 || $rows->status==7){
            $return_data.=' <a class="btn btn-primary upload_vendor_btn" data-id="'.$id.'" data-surat="'.$no_surat.'" title="Send Digisign" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-file-upload"></i></a>';
        }
        if($rows->status==4 || $rows->status==5 || $rows->status==7){
            $return_data.=' <a class="btn btn-primary send_act_btn" data-id="'.$id.'" title="Kirim" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fas fa-envelope-square"></i></a>';
        }
        if($rows->status==7){
            $return_data.=' <a class="btn btn-xs btn-primary sign_act_btn" data-id="'.$id.'" title="Sign" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;"><i class="fa fa-signature"></i></a>';
        }
		return $return_data;
	}

	function label_status_masuk($data, $id)
	{
		switch ($data) {
			case 0:
				return '<span class="label popup label-danger" data-id="'.$id.'">Mail Created</span>';
				break;
			case 1:
				return '<span class="label popup label-primary" data-id="'.$id.'">Doc.Uploaded</span>';
				break;
			case 2:
				return '<span class="label popup label-warning" data-id="'.$id.'">Sign.Set</span>';
				break;
			case 3:
				return '<span class="label popup label-success" data-id="'.$id.'">Doc.Failed</span>';
				break;
			case 4:
				return '<span class="label popup label-primary" data-id="'.$id.'">Doc.Sent</span>';
				break;
			case 5:
				return '<span class="label popup label-warning" data-id="'.$id.'">Waiting.Approval</span>';
				break;
			case 6:
				return '<span class="label popup label-success" data-id="'.$id.'">Approval.Cancel</span>';
				break;
			case 7:
				return '<span class="label popup label-primary" data-id="'.$id.'">Sign.Approved</span>';
				break;
			case 8:
				return '<span class="label popup label-warning" data-id="'.$id.'">Doc.Signed</span>';
				break;
			default:
				return '<span class="label popup label-primary" data-id="'.$id.'">Undetected</span>';
				break;
		}
	}

	function label_posisi($data, $id)
	{
		switch ($data) {
			case 0:
				return '<span class="label popup label-danger" data-id="'.$id.'">Kiri Bawah</span>';
				break;
			case 1:
				return '<span class="label popup label-primary" data-id="'.$id.'">Kanan Bawah</span>';
				break;
			default:
				return '<span class="label popup label-primary" data-id="'.$id.'">Undetected</span>';
				break;
		}
	}

	function label_approval($data, $id)
	{
		switch ($data) {
			case 0:
				return '<span class="label popup label-danger" data-id="'.$id.'">Pending</span>';
				break;
			case 1:
				return '<span class="label popup label-primary" data-id="'.$id.'">Waiting Approve</span>';
				break;
			case 2:
				return '<span class="label popup label-primary" data-id="'.$id.'">Cancel</span>';
				break;
			default:
				return '<span class="label popup label-primary" data-id="'.$id.'">Approve</span>';
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

	function select_posisi($data)
    {
		echo '<option value="0">Kiri Bawah</option>';
		echo '<option value="1">Kanan Bawah</option>';
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

	function select_vendor($data)
    {
        echo '<option value="1" selected="selected">Digisign</option>';
	}

	function select_approval($data)
    {
        $query = $this->db->query('SELECT id, name FROM t_signer');
		if(empty($data)){
			foreach($query->result() as $id) {
				echo '<option value="'.$id->id.'">'.$id->name.'</option>';
			}
		}else{
			foreach($query->result() as $id) {
				if(strstr($data, $id->id) != FALSE){
					echo '<option value="'.$id->id.'" selected="selected">'.$id->name.'</option>';
				}else{
					echo '<option value="'.$id->id.'">'.$id->name.'</option>';
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

	function nama_penerima($id)
	{
		$query = $this->db->query('SELECT name FROM t_signer WHERE id='.$id.' LIMIT 1');
		$name = $query->row();
		if(isset($name)){
			return $name->name;
		}else{
			return NULL;
		}
	}
}