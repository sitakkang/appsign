<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profil extends CI_Controller {

	public $dir_v = 'admin/profil/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

	public function __construct(){
		parent::__construct();
		$this->m_auth->check_login();
		$this->load->library('L_auth');
	}

	function index()
	{
		$data['css'] = array("lib/dropzone/dropzone.min.css");
		$data['js'] = array('lib/dropzone/dropzone.min.js', 'src/js/admin/profil.js');
		$data['panel'] = '<i class="fa fa-cog"></i> &nbsp;<b>Pengaturan Akun</b>';
		$this->l_skin->main($this->dir_v.'view', $data);
	}

	function edit()
	{
		$id = $this->session->userdata('sess_id');
		$result_id = $this->db->query('SELECT * FROM conf_users WHERE id_user='.$id.' LIMIT 1');
		$data['id'] = $result_id->row();
		$this->load->view($this->dir_v.'edit', $data);
	}

	function act_edit()
	{
		$id = $this->input->post('id_tbl');
		$username = $this->input->post('username');
		$username_old = $this->input->post('username_old');
		if($username === $username_old){
			$this->form_validation->set_rules('fullname', 'Fullname', 'trim|required|min_length[4]');
			if ($this->form_validation->run() == FALSE){
				$notif['notif'] = validation_errors();
				$notif['status'] = 1;
				echo json_encode($notif);
			}else{
				$data = array(
					'fullname' => $this->input->post('fullname')
				);
				$this->db->where('id_user', $id);
				$this->db->update('conf_users', $data);
				$notif['notif'] = 'Data User <b>'.$this->input->post('fullname').'</b> berhasil disimpan !';
                $notif['status'] = 2;
                echo json_encode($notif);
			}
		}else{
			$this->form_validation->set_rules('fullname', 'Fullname', 'trim|required|min_length[4]');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|is_unique[conf_users.username]');
			if ($this->form_validation->run() == FALSE){
				$notif['notif'] = validation_errors();
				$notif['status'] = 1;
				echo json_encode($notif);
			}else{
				$data = array(
					'fullname' => $this->input->post('fullname'),
					'username' => $this->input->post('username')
				);
				$this->db->where('id_user', $id);
				$this->db->update('conf_users', $data);
				$notif['notif'] = 'Data User <b>'.$this->input->post('fullname').'</b> berhasil disimpan !';
                $notif['status'] = 2;
                echo json_encode($notif);
			}
		}
		$this->session->unset_userdata('sess_name');
		$this->session->set_userdata('sess_name', $data['fullname']);
	}

	function upload_ava()
	{
		$this->load->view($this->dir_v.'add');
	}

	function act_upload_ava()
	{
		$id = $this->session->userdata('sess_id');
		$result_id = $this->db->query('SELECT avatar FROM conf_users WHERE id_user='.$id.' LIMIT 1');
		$data_user = $result_id->row();
		if(!empty($data_user->avatar)){
			$old_pic = './'.$data_user->avatar;
	    	unlink($old_pic);
		}
        $target_folder = 'avatar';
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];
            $tempName = $_FILES['file']['name'];
            $ext_file = pathinfo($tempName, PATHINFO_EXTENSION);
            $fileName = $this->l_auth->rand_str1(8, FALSE).'.'.$ext_file;
            $targetPath = './img/'.$target_folder.'/';
            $targetFile = $targetPath.$fileName;
            $data = array(
                'avatar' => 'img/'.$target_folder.'/'.$fileName
            );
            $this->db->where('id_user', $id);
            if($this->db->update('conf_users', $data)){
                move_uploaded_file($tempFile, $targetFile);
        		$config['image_library'] = 'gd2';
                $config['source_image'] = $targetPath.$fileName;
                $config['new_image'] = $targetPath.$fileName;
                $config['maintain_ratio'] = TRUE;
                $config['height'] = 225;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
                $this->session->unset_userdata('sess_avatar');
				$this->session->set_userdata('sess_avatar', $targetFile);
            }else{
                echo $this->db->error();
            }
        }
	}

	// Edit Password
	function edit_pass()
	{
		$this->load->view($this->dir_v.'edit_pass');
	}

	function act_edit_pass()
	{
		$id = $this->session->userdata('sess_id');
        $old_pass = $this->input->post('old_password');
        if($this->cek_pass($old_pass) == TRUE){
            $this->form_validation->set_rules('new_password', 'Password Baru', 'trim|required|min_length[5]|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'trim|required');
            if ($this->form_validation->run() == FALSE){
                $notif['notif'] = validation_errors();
				$notif['status'] = 1;
				echo json_encode($notif);
            }else{
                $new_pass = $this->input->post('new_password');
                $salt = $this->l_auth->rand_str1(8, TRUE);
                $encrypt_pass = $this->l_auth->encrypt_pass($new_pass, $salt);
                $data = array(
                    'password' => $encrypt_pass,
                    'salt' => $salt
                );
                $this->db->where('id_user', $id);
                $this->db->update('conf_users', $data);
                $notif['notif'] = 'Password baru anda berhasil di simpan !';
                $notif['status'] = 2;
                echo json_encode($notif);
            }
        }else{
        	$notif['notif'] = "Password lama yang anda masukan salah !";
        	$notif['status'] = 1;
			echo json_encode($notif);
		}
	}

	public function cek_pass($old_pass){
		$id = $this->session->userdata('sess_id');
		if($old_pass != NULL){
			$query = $this->db->query("SELECT password, salt FROM conf_users WHERE id_user = '".$id."' LIMIT 1");
			if ($query->num_rows() == 1){
				$row = $query->row();
				$user_pass = $row->password;
				$user_salt = $row->salt;
				if($this->l_auth->encrypt_pass($old_pass, $user_salt) === $user_pass){
					return TRUE;
				}else{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

	// function crop_pic()
	// {
	// 	$id = $this->session->userdata('sess_id');
	// 	$query = $this->db->query("SELECT avatar FROM conf_users WHERE id_user = '".$id."' LIMIT 1");
	// 	if ($query->num_rows() == 1){
	// 		$row = $query->row();
	// 		$data['avatar'] = $row->avatar;
	// 		$this->load->view('backend/profil/crop_pic', $data);
	// 	}else{
	// 		echo 'Avatar is not found in the database !';
	// 	}
	// }

	// function act_crop_pic()
	// {
	// 	$this->load->library("Myimage");

	// 	$x1 = $this->input->post('x1_img');
	// 	$y1 = $this->input->post('y1_img');
	// 	$x2 = $this->input->post('x2_img');
	// 	$y2 = $this->input->post('y2_img');
	// 	$from = './'.$this->input->post('url_img');
	// 	$to = './'.$this->input->post('url_img');

	// 	$result = $this->myimage->cropping($from, $to, $x1, $y1, $x2, $y2);

	// 	if($result){
	// 		$notif['notif'] = 'Error Cropping !';
	// 		$notif['status'] = 1;
	// 		echo json_encode($notif);
	// 	}else{
	// 		$notif['notif'] = 'Image has been Croped !';
	// 		$notif['status'] = 2;
	// 		echo json_encode($notif);
	// 	}

	// }

}