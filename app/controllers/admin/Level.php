<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Level extends CI_Controller {

	public $dir_v = 'admin/level/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

	public function __construct(){
		parent::__construct();
		$this->m_auth->check_superadmin();
	}

	function index()
	{
		$data['css'] = array(
			'lib/datatables/dataTables.bootstrap.min.css'
		);
		$data['js'] = array(
			'lib/datatables/datatables.min.js',
			'lib/datatables/dataTables.bootstrap.min.js',
			'src/js/admin/level.js'
		);
		$data['panel'] = '<i class="fa fa-lock"></i> &nbsp;<b>Akses Level</b>';
		$this->l_skin->config($this->dir_v.'view', $data);
	}

	function tabel()
	{
		$get_all = $this->db->query('SELECT * FROM conf_level ORDER BY id_level');

		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$data = array();
		$i = 1;
		foreach($get_all->result() as $id) {
			$data[] = array(
			    "DT_RowId" => $id->id_level,
			    "0" => $id->id_level,
			    "1" => $id->name,
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

	function add()
	{
		$this->load->view($this->dir_v.'add');
	}

	function edit()
	{
		$data_id = $this->input->get('id_tbl');
		$result_id = $this->db->query('SELECT * FROM conf_level WHERE id_level='.$data_id.' LIMIT 1');
		$data['id'] = $result_id->row();
		$this->load->view($this->dir_v.'edit', $data);
	}

	// ACTION POST
	function act_add()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[4]|is_unique[conf_level.name]');
		if ($this->form_validation->run() == FALSE){
			$notif['notif'] = validation_errors();
			$notif['status'] = 1;
			echo json_encode($notif);
		}else{
			$data = array(
				'name' => $this->input->post('name')
			);
			$this->db->insert('conf_level', $data);
			$notif['lastid'] = $this->db->insert_id();
			$notif['notif'] = 'Data level '.$this->input->post('name').' berhasil disimpan !';
			$notif['status'] = 2;
			echo json_encode($notif);
		}
	}

	function act_edit()
	{
		$id = $this->input->post('id_tbl');
		if($this->input->post('name') === $this->input->post('name_old')){
			$notif['notif'] = 'Data '.$this->input->post('name').' berhasil di ubah !';
            $notif['status'] = 2;
            echo json_encode($notif);
		}else{
			$this->form_validation->set_rules('name', 'name', 'trim|required|min_length[4]|is_unique[conf_level.name]');
			if ($this->form_validation->run() == FALSE){
				$notif['notif'] = validation_errors();
				$notif['status'] = 1;
				echo json_encode($notif);
			}else{
				$data = array(
					'name' => $this->input->post('name')
				);
				$this->db->where('id_level', $id);
				$this->db->update('conf_level', $data);
				$notif['notif'] = 'Data '.$this->input->post('name').' berhasil di ubah !';
                $notif['status'] = 2;
                echo json_encode($notif);
			}
		}
	}

	function act_del()
	{
		$id = $this->input->post('id_tbl');
		$this->db->select();
    	$this->db->from("conf_users");
    	$this->db->where("level",$id);
    	$this->db->limit(1);
    	$query = $this->db->get();
    	if($query->num_rows() == 1){
    		$notif['notif']  = 'Data level masih di pakai user, unset dulu semua user yang memiliki level ini di menu edit user !';
			$notif['status'] = 1;
		}else{
			$this->db->where('id_level', $id);
			$this->db->delete('conf_level');
			$notif['notif']  = 'Data '.$this->input->post('name').' berhasil di hapus !';
			$notif['status'] = 2;
		}
        echo json_encode($notif);
	}

}