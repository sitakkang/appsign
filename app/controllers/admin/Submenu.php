<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Submenu extends CI_Controller {

	public $dir_v = 'admin/submenu/';
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
			'src/js/admin/submenu.js'
		);
		$data['panel'] = '<i class="fa fa-list-ul"></i> &nbsp;<b>Data Sub Menu</b>';
		$this->l_skin->config($this->dir_v.'view', $data);
	}

	function table($akses)
	{
		$get_all = $this->db->query('SELECT a.id_submenu,a.name,a.link,a.status,a.level,a.position,a.icon as icon,a.icon2,c.name as main_menu FROM conf_submenu a JOIN conf_menu c ON a.id_menu=c.id_menu WHERE c.akses='.$akses.' ORDER BY a.position');

		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = array();
		foreach($get_all->result() as $id){
			$data[] = array(
			    "DT_RowId" => $id->id_submenu,
			    "0" => $id->main_menu,
			    "1" => $id->position,
			    "2" => $this->iconCheck($id->icon),
			    "3" => $id->icon2,
			    "4" => $id->name,
			    "5" => $id->link,
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
		$query = $this->db->query('SELECT id_menu FROM conf_menu WHERE akses='.$akses.' AND sub=2 LIMIT 1');
		$menu = $query->row();
		if(isset($menu->id_menu)){
			$data['akses'] = $akses;
			$this->load->view($this->dir_v.'add', $data);
		}else{
			echo '<div class="alert alert-danger" role="alert"><p class="text-center">Data <b>Main Menu</b> masih kosong atau <b>Submenu</b> tidak diaktifkan pada <b>Main Menu</b> !</p></div>';
		}
	}

	function edit($akses)
	{
		$data_id = $this->input->get('id_tbl');
		$result_id = $this->db->query('SELECT * FROM conf_submenu WHERE id_submenu='.$data_id.' LIMIT 1');
		$data['id'] = $result_id->row();
		$data['akses'] = $akses;
		$this->load->view($this->dir_v.'edit', $data);
	}

	// ACTION POST
	function act_add()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|is_unique[conf_submenu.name]');
		if ($this->form_validation->run() == FALSE){
			$notif['notif'] = validation_errors();
			$notif['status'] = 1;
			echo json_encode($notif);
		}else{
			$level = $this->l_admin->filter_array($this->input->post('level'));
            if($level == '""'){$result_level = '';}else{$result_level = $level;}
			$data = array(
					'id_menu' => $this->input->post('id_menu'),
					'icon' => $this->input->post('icon'),
					'icon2' => $this->input->post('icon2'),
					'name' => $this->input->post('name'),
					'link' => $this->input->post('link'),
					'status' => $this->input->post('status'),
					'level' => $result_level,
                    'position' => $this->input->post('position')
				);
			$this->db->insert('conf_submenu', $data);
			$notif['notif'] = 'Data Sub Menu '.$this->input->post('name').' berhasil di simpan !';
			$notif['status'] = 2;
			echo json_encode($notif);
			$this->write_json_file_sub_menu();
		}
	}

	function act_edit()
	{
		$id = $this->input->post('id_tbl');
		if($this->input->post('name_old') === $this->input->post('name')){
			$level = $this->l_admin->filter_array($this->input->post('level'));
            if($level == '""'){$result_level = '';}else{$result_level = $level;}
			$data = array(
					'id_menu' => $this->input->post('id_menu'),
					'icon' => $this->input->post('icon'),
					'icon2' => $this->input->post('icon2'),
					'link' => $this->input->post('link'),
					'status' => $this->input->post('status'),
					'level' => $result_level,
                    'position' => $this->input->post('position')
				);
			$this->db->where('id_submenu', $id);
            $this->db->update('conf_submenu', $data);
			$notif['notif'] = 'Data Sub Menu '.$this->input->post('name').' berhasil di ubah !';
			$notif['status'] = 2;
			echo json_encode($notif);
			$this->write_json_file_sub_menu();
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
						'id_menu' => $this->input->post('id_menu'),
						'icon' => $this->input->post('icon'),
						'icon2' => $this->input->post('icon2'),
						'name' => $this->input->post('name'),
						'link' => $this->input->post('link'),
						'status' => $this->input->post('status'),
						'level' => $result_level,
	                    'position' => $this->input->post('position')
					);
				$this->db->where('id_submenu', $id);
            	$this->db->update('conf_submenu', $data);
				$notif['notif'] = 'Menu '.$this->input->post('name').' berhasil di ubah !';
				$notif['status'] = 2;
				echo json_encode($notif);
				$this->write_json_file_sub_menu();
			}
		}
	}

	function act_del()
	{
		$id = $this->input->post('id_tbl');
		$name = $this->input->post('name');
		$this->db->where('id_submenu', $id);
		$this->db->delete('conf_submenu');
		$notif['notif'] = 'Data Sub Menu '.$this->input->post('name').' berhasil di hapus !';
		$notif['status'] = 2;
        echo json_encode($notif);
        $this->write_json_file_sub_menu();
	}

	function write_json_file_sub_menu()
	{
		$get_all = $this->db->query('SELECT * FROM conf_submenu ORDER BY position ASC');
		$json_file = array();
		foreach ($get_all->result() as $val) {
			$json_file[] = array(
				'id_submenu' 	=> $val->id_submenu,
				'id_menu'		=> $val->id_menu,
				'icon'			=> $val->icon,
				'icon2'			=> $val->icon2,
				'name'			=> $val->name,
				'link'			=> $val->link,
				'status'		=> $val->status,
				'level'			=> $val->level,
				'position'		=> $val->position
			);
		}
		$this->load->helper('file');
		write_file('./src/json/sub_menu.json', json_encode($json_file));
	}

}