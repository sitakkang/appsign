<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {

	public $dir_v = 'admin/menu/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

	public function __construct(){
		parent::__construct();
		$this->m_auth->check_superadmin();
		$this->load->model($this->dir_m.'m_admin');
		$this->load->library($this->dir_l.'l_admin');
	}

	function index()
	{
		$data['css'] = array(
			'lib/datatables/dataTables.bootstrap.min.css',
			'lib/select/component-chosen.min.css',
			'lib/iconpicker/css/bootstrap-iconpicker.min.css'
		);
		$data['js'] = array(
			'lib/datatables/datatables.min.js',
			'lib/datatables/dataTables.bootstrap.min.js',
			'lib/select/chosen.jquery.min.js',
			'lib/iconpicker/js/solid-icon-fontawesome.js',
			'lib/iconpicker/js/bootstrap-iconpicker.min.js',
			'src/js/admin/menu.js'
		);
		$data['panel'] = '<i class="fa fa-th-list"></i> &nbsp;<b>Data Menu</b>';	
		$this->l_skin->config($this->dir_v.'view', $data);
	}

	function table($akses)
	{
		$get_all = $this->db->query('SELECT * FROM conf_menu WHERE akses='.$akses.' ORDER BY position'); 

		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = array();
		foreach($get_all->result() as $id) {
			$data[] = array(
			    "DT_RowId" => $id->id_menu,
			    "0" => $id->position,
			    "1" => $this->iconCheck($id->icon),
			    "2" => $id->icon2,
			    "3" => $id->name,
			    "4" => $id->link,
			    "5" => $this->l_admin->yes_or_no($id->sub),
			    "6" => $this->l_admin->status_aktif($id->status)
			);
         }

        $output = array(
			"draw" => $draw,
			"recordsTotal" => $get_all->num_rows(),
			"recordsFiltered" => $get_all->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	function iconCheck($data)
	{
		if (strpos($data, 'fa-') !== false) {
			return '<i class="fa '.$data.'"></i>';
		}else{
			return $data;
		}
	}

	function add($akses)
	{
		$data['akses'] = $akses;
		$this->load->view($this->dir_v.'add', $data);
	}

	function edit($akses)
	{
		$data_id = $this->input->get('id_tbl');
		$result_id = $this->db->query('SELECT * FROM conf_menu WHERE id_menu='.$data_id.' LIMIT 1');
		$data['id'] = $result_id->row();
		$data['akses'] = $akses;
		$this->load->view($this->dir_v.'edit', $data);
	}

	function act_add()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|is_unique[conf_menu.name]');
		if ($this->form_validation->run() == FALSE){
			$notif['notif'] = validation_errors();
			$notif['status'] = 1;
			echo json_encode($notif);
		}else{
			$level = $this->l_admin->filter_array($this->input->post('level'));
            if($level == '""'){$result_level = '';}else{$result_level = $level;}
			$data = array(
					'icon' => $this->input->post('icon'),
					'icon2' => $this->input->post('icon2'),
					'name' => $this->input->post('name'),
					'link' => $this->input->post('link'),
					'status' => $this->input->post('status'),
					'akses' => $this->input->post('akses'),
                    'sub' => $this->input->post('sub'),
					'level' => $result_level,
                    'position' => $this->input->post('position')
				);
			$this->db->insert('conf_menu', $data);
			$notif['notif'] = 'Menu '.$this->input->post('name').' berhasil disimpan !';
			$notif['status'] = 2;
			echo json_encode($notif);
			$this->write_json_file_main_menu();
		}
	}

	function act_edit()
	{
		$id = $this->input->post('id_tbl');
		if($this->input->post('name_old') === $this->input->post('name')){
			$level = $this->l_admin->filter_array($this->input->post('level'));
            if($level == '""'){$result_level = '';}else{$result_level = $level;}
			$data = array(
					'icon' => $this->input->post('icon'),
					'icon2' => $this->input->post('icon2'),
					'link' => $this->input->post('link'),
					'status' => $this->input->post('status'),
					'akses' => $this->input->post('akses'),
                    'sub' => $this->input->post('sub'),
					'level' => $result_level,
                    'position' => $this->input->post('position')
				);
			$this->db->where('id_menu', $id);
            $this->db->update('conf_menu', $data);
			$notif['notif'] = 'Menu '.$this->input->post('name').' berhasil di ubah !';
			$notif['status'] = 2;
			echo json_encode($notif);
			$this->write_json_file_main_menu();
		}else{
			$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|is_unique[conf_menu.name]');
			if ($this->form_validation->run() == FALSE){
				$notif['notif'] = validation_errors();
				$notif['status'] = 1;
				echo json_encode($notif);
			}else{
				$level = $this->l_admin->filter_array($this->input->post('level'));
	            if($level == '""'){$result_level = '';}else{$result_level = $level;}
				$data = array(
						'icon' => $this->input->post('icon'),
						'icon2' => $this->input->post('icon2'),
						'name' => $this->input->post('name'),
						'link' => $this->input->post('link'),
						'status' => $this->input->post('status'),
						'akses' => $this->input->post('akses'),
	                    'sub' => $this->input->post('sub'),
						'level' => $result_level,
	                    'position' => $this->input->post('position')
					);
				$this->db->where('id_menu', $id);
	            $this->db->update('conf_menu', $data);
				$notif['notif'] = 'Menu '.$this->input->post('name').' berhasil di ubah !';
				$notif['status'] = 2;
				echo json_encode($notif);
				$this->write_json_file_main_menu();
			}
		}
	}

	function cek_sub_menu($id)
	{
		$query = $this->db->query('SELECT id_menu FROM conf_submenu WHERE id_menu='.$id.' LIMIT 1');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	function act_del()
	{
		$id = $this->input->post('id_tbl');
		$name = $this->input->post('name');
		if($this->cek_sub_menu($id)){
			$notif['notif'] = 'Data Menu <b>'.$name.'</b> tidak bisa di hapus karena masih memiliki data Sub Menu !';
            $notif['status'] = 1;
            echo json_encode($notif);
		}else{
			$this->db->where('id_menu', $id);
			$this->db->delete('conf_menu');
			$notif['notif'] = 'Data '.$this->input->post('name').' berhasil di hapus !';
			$notif['status'] = 2;
	        echo json_encode($notif);
	        $this->write_json_file_main_menu();
		}
	}

	function write_json_file_main_menu()
	{
		$get_all = $this->db->query('SELECT * FROM conf_menu ORDER BY position ASC');
		$json_file = array();
		foreach ($get_all->result() as $val) {
			$json_file[] = array(
				'id_menu' 	=> $val->id_menu,
				'icon'		=> $val->icon,
				'icon2'		=> $val->icon2,
				'name'		=> $val->name,
				'link'		=> $val->link,
				'status'	=> $val->status,
				'akse'		=> $val->akses,
				'sub'		=> $val->sub,
				'level'		=> $val->level,
				'position'	=> $val->position
			);
		}
		$this->load->helper('file');
		write_file('./src/json/main_menu.json', json_encode($json_file));
	}
}