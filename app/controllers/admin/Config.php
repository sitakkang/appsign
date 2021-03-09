<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config extends CI_Controller {

	public $dir_v = 'admin/config/';
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
		$data['css'] = array("lib/dropzone/dropzone.min.css");
		$data['js'] = array('lib/dropzone/dropzone.min.js','src/js/admin/config.js');
		$this->l_skin->config($this->dir_v.'view', $data);
	}

	function edit()
	{
		$get_all = $this->db->query('SELECT * FROM conf_label WHERE id_label=1 LIMIT 1');
		$data['id'] = $get_all->row();
		$this->load->view($this->dir_v.'edit', $data);
	}

	function act_edit()
	{
		$id = $this->input->post('id_tbl');
		$this->form_validation->set_rules('title_front', 'Title Front', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('title_back', 'Title Back', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('email_', 'Email', 'trim|required|min_length[5]|valid_email');
		$this->form_validation->set_rules('footer_', 'Footer', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('meta_description', 'Meta Desription', 'trim|required|min_length[5]');
		$this->form_validation->set_rules('meta_keyword', 'Meta Keyword', 'trim|required|min_length[5]');
		if ($this->form_validation->run() == FALSE){
			$notif['notif'] = validation_errors();
			$notif['status'] = 1;
			echo json_encode($notif);
		}else{
			$data = array(
				'title_front' => $this->input->post('title_front'),
				'title_back' => $this->input->post('title_back'),
				'email_' => $this->input->post('email_'),
				'footer_' => $this->input->post('footer_'),
				'meta_description' => $this->input->post('meta_description'),
				'meta_keyword' => $this->input->post('meta_keyword')
			);
			$this->db->where('id_label', $id);
			$this->db->update('conf_label', $data);
			$notif['notif'] = 'Data pengaturan berhasil diperbarui !';
			$notif['status'] = 2;
			echo json_encode($notif);
		}
	}

	function upload()
	{
		$this->load->view($this->dir_v.'upload');
	}

	function act_upload($fileName)
	{
        if (!empty($_FILES)) {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetPath = './img/imip/';
            $targetFile = $targetPath.$fileName;
            move_uploaded_file($tempFile, $targetFile);
        }
	}

	function backup_db_apps()
	{
		date_default_timezone_set('UTC');
		$this->load->dbutil();
        $config = array(
        	'tables'	=> array('apps_attach', 'apps_batch', 'apps_perusahaan', 'apps_master', 'apps_proses'),
			'format'	=> 'zip',            
			'filename'	=> 'db_kitas_apps.sql'
		);
        $backup = $this->dbutil->backup($config);
        $nama_database = 'backup_db_kitas_apps_'.gmdate('Ymdhis', time()+60*60*7).'.zip';
        $this->load->helper('download');
        force_download($nama_database, $backup);
	}

	function backup_db_conf()
	{
		date_default_timezone_set('UTC');
		$this->load->dbutil();
        $config = array(
        	'tables'	=> array('conf_level', 'conf_menu', 'conf_submenu', 'conf_users'),
			'format'	=> 'zip',
			'filename'	=> 'db_conf.sql'
		);
        $backup = $this->dbutil->backup($config);
        $nama_database = 'backup_db_tbl_conf_'.gmdate('Ymdhis', time()+60*60*7).'.zip';
        $this->load->helper('download');
        force_download($nama_database, $backup);
	}

}