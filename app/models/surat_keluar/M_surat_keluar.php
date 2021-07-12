<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	/*
	status Surat
	O = Mail Created
	1 =  Upload Document
	2 = Signature set
	5 = Waiting Approval
	6 = Approval Cancel
	7 = Approve Signature
	3 = Document Failed//
	4 = Document Sent//
	
	
	8 = Document Signed


	0 = Mail Created
	1 = Upload Document
	2 = Signature Set
	3 = Waiting Approval
	4 = Cancel Sign
	5 = Document Signed
	6 = Token Expired
	7 = Approved but not Downloaded
    */

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
						$view .= '<a href="" class="btn_preview_mail" title="View Origin Attachment" data-tipe="1" data-url="'.$key.'" style="color:#0F9647;"><button><i class="fa fa-paperclip"></i></button></a>';
					}else{
						$view .= '<a href="" class="btn_preview_mail" data-tipe="2" data-url="'.$key.'" style="color:#0F9647;"><button><i class="fa fa-images"></i></button></a>';
					}
				}else{
					$view .= '';
				}
			}
			return $view;
		}
	}

	function attachment_downloaded($data)
	{
		if(is_array($data)){
			$view = '';
			foreach ($data as $key) {
				if(isset($key)){
					$char = substr($key, -3);
					if($char == 'pdf'){
						$view .= '<a href="" class="btn_preview_mail" title="View Approved Attachment" data-tipe="1" data-url="'.$key.'" style="color:#0F9647;"><button><i class="fa fa-envelope-open-text"></i></button></a>';
					}else{
						$view .= '<a href="" class="btn_preview_mail" data-tipe="2" data-url="'.$key.'" style="color:#0F9647;"><button><i class="fa fa-images"></i></button></a>';
					}
				}else{
					$view .= '';
				}
			}
			return $view;
		}
	}

	function get_signer($data)
    {
        $nama="";
        if(!empty($data)){
            $query = $this->db->query('SELECT id, name FROM t_signer where id="'.$data.'" LIMIT 1');
            $id = $query->row();
            $nama=$id->name;
        }
        return $nama; 
    }

    function get_user($data)
    {
        $query = $this->db->query('SELECT fullname FROM conf_users WHERE id_user='.$data.' LIMIT 1');
		$name = $query->row();
		if(isset($name)){
			return $name->fullname;
		}else{
			return NULL;
		}
    }

	function keluar_act_btn($id, $no_surat)
	{
		$user_id = $this->session->userdata('sess_id');
		$return_data='';
		$query = $this->db->query('SELECT * FROM app_surat_keluar WHERE id_surat_keluar ='.$id.' LIMIT 1');

        $rows = $query->row();
        $status=$rows->status;
        if($rows->user==$user_id && $status!=5){
        	$return_data.='<a href="" class="upload_act_btn" data-jenis_ttd="'.$rows->jenis_ttd.'" data-id="'.$id.'" title="Upload" style="color:#0F9647;"><button><i class="fas fa-upload"></i></button></a>';
        }	
        $return_data.='
        <a href="" class="view_act_btn" data-id="'.$id.'" title="View Detail" style="color:#0F9647;"><button><i class="fa fa-search"></i></button></a>
        ';
        if($rows->user==$user_id && $status ==0 || $status ==1){
        	$return_data.='<a href="" class="edit_act_btn" data-id="'.$id.'" title="Edit" style="color:#0F9647;"><button><i class="fas fa-edit"></i></button></a>
			<a href="" class="delete_act_btn" data-id="'.$id.'" data-surat="'.$no_surat.'" title="Hapus" style="color:#BD2130;"><button><i class="fas fa-trash"></i></button></a>';
			
        }
        if(($rows->status==1 || $rows->status==2) && ($rows->jenis_ttd=="Digital") && $rows->user==$user_id){
            $return_data.='
            <a href="sign_pdf/'.$id.'" class="posisi_act_btn" data-id="'.$id.'" title="Posisi" style="color:#0F9647;"><button><i class="fas fa-file-powerpoint"></button></i></a> ';
        }
        
        if(($rows->jenis_ttd=="Manual") && ($rows->user==$user_id)){
            $return_data.=' <a href="" class="status_act_btn" data-id="'.$id.'" title="Status" style="color:#0F9647;"><button><i class="fas fa-calendar-plus"></i></button></a>';
        }
		return $return_data;
	}


	function label_jenis_ttd($data){
		switch ($data) {
			case 'Digital':
				return '<span class="badge badge-danger">Digital</span>';
				break;
			case 'Manual':
				return '<span class="badge badge-warning">Manual</span>';
				break;
		default:
				return '<span class="badge badge-dark">Undetected</span>';
				break;	
		}
	}

	function label_status_keluar($data, $id)
	{
		switch ($data) {
			case 0:
				return '<span class="badge badge-secondary">Mail Created</span>';
				break;
			case 1:
				return '<span class="badge badge-info">Doc.Uploaded</span>';
				break;
			case 2:
				return '<span class="badge badge-warning">Sign.Set</span>';
				break;
			case 3:
				return '<span class="badge badge-primary">Waiting Approval</span>';
				break;
			case 4:
				return '<span class="badge badge-danger">Rejected Signature</span>';
				break;
			case 5:
				return '<span class="badge badge-success">Document Signed</span>';
				break;
			case 6:
				return '<span class="badge badge-dark">Token Expired</span>';
				break;
			case 7:
				return '<span class="badge badge-dark">Approved Not Downloaded</span>';
				break;
			default:
				return '<span class="badge badge-dark">Undetected</span>';
				break;
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

	function user_surat_keluar($id)
	{
		$query = $this->db->query('SELECT fullname FROM conf_users WHERE id_user='.$id.' LIMIT 1');
		$name = $query->row();
		if(isset($name)){
			return $name->fullname;
		}else{
			return NULL;
		}
	}

	function select_vendor($data)
    {
        echo '<option value="1" selected="selected">Digisign</option>';
	}

	function asal_surat($data)
	{
		$opt_srt = array(
			"Email",
			"Fax",
			"Lainnya"
		);
		foreach($opt_srt as $val) {
			if($data == $val){
				echo '<option value="'.$val.'" selected>'.$val.'</option>';
			}else{
				echo '<option value="'.$val.'">'.$val.'</option>';
			}
			
		}
	}


	function select_jenisttd($data)
	{
		$opt_srt = array(
			"Digital",
			"Manual"
		);
		echo '<option value="" disabled selected hidden>Jenis Tandatangan</option>';
		foreach($opt_srt as $val) {
			if($data == $val){
				echo '<option value="'.$val.'" selected>'.$val.'</option>';
			}else{
				echo '<option value="'.$val.'">'.$val.'</option>';
			}
			
		}
	}

	function select_approval($data,$id)
    {
        $query = $this->db->query('SELECT id, name FROM t_signer where user_id='.$id.'');
		if(empty($data)){
			echo '<option value="" disabled selected hidden>Pilih Penanda tangan</option>';
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

	function select_approval_admin($data)
    {
        $query = $this->db->query('SELECT id, name, email_user FROM t_signer where id='.$data.' LIMIT 1');
		foreach($query->result() as $id) {
			if(strstr($data, $id->id) != FALSE){
				echo '<option value="'.$id->id.'" selected="selected">'.$id->name.' ('.$id->email_user.')</option>';
			}else{
				echo '<option value="'.$id->id.'">'.$id->name.'</option>';
			}
		}
	}

	function bentuk_surat($data)
	{
		$opt_srt = array(
			"Softcopy",
			"Hardcopy"
		);
		echo '<option value="" disabled selected hidden>Pilih Type Surat</option>';
		foreach($opt_srt as $val) {
			if($data == $val){
				echo '<option value="'.$val.'" selected>'.$val.'</option>';
			}else{
				echo '<option value="'.$val.'">'.$val.'</option>';
			}
			
		}
	}

	function select_status_manual($data)
	{
		$opt_srt = array(
			0=>'Mail Created',
			1=>'Doc.Uploaded',
			3=>'Waiting Approval',
			4=>'Rejected Signature',
			5=>'Document Signed'
		);
		echo '<option value="" disabled selected hidden>Pilih Status</option>';
		foreach($opt_srt as $key => $val) {
			if($data == $key){
				echo '<option value="'.$key.'" selected>'.$val.'</option>';
			}else{
				echo '<option value="'.$key.'">'.$val.'</option>';
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
}