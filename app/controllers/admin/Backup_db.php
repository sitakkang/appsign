<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup_db extends CI_Controller {

	public $dir_v = 'admin/backup/';

	public function __construct(){
		parent::__construct();
		$this->m_auth->check_superadmin();
	}

	function index()
	{
		$this->l_skin->config($this->dir_v.'view');
	}

	function backup_db_apps()
	{
		// date_default_timezone_set('UTC');
		// $this->load->dbutil();
        // $config = array(
        // 	'tables'	=> array('apps_attach', 'apps_batch', 'apps_perusahaan', 'apps_master', 'apps_proses'),
		// 	'format'	=> 'zip',            
		// 	'filename'	=> 'db_kitas_apps.sql'
		// );
        // $backup = $this->dbutil->backup($config);
        // $nama_database = 'backup_db_kitas_apps_'.gmdate('Ymdhis', time()+60*60*7).'.zip';
        // $this->load->helper('download');
        // force_download($nama_database, $backup);
	}

	function backup_db_tbl()
	{
		// date_default_timezone_set('UTC');
		// $this->load->dbutil();
        // $config = array(
        // 	'tables'	=> array('tbl_config', 'tbl_icon', 'tbl_level', 'tbl_menu', 'tbl_sub_menu', 'tbl_users'),
		// 	'format'	=> 'zip',
		// 	'filename'	=> 'db_kitas_tbl.sql'
		// );
        // $backup = $this->dbutil->backup($config);
        // $nama_database = 'backup_db_kitas_tbl_'.gmdate('Ymdhis', time()+60*60*7).'.zip';
        // $this->load->helper('download');
        // force_download($nama_database, $backup);
	}

	function backup_db_all()
	{
		date_default_timezone_set('UTC');
		$this->load->dbutil();
        $config = array(
			'format'	=> 'zip',
			'filename'	=> 'db_hris.sql'
		);
        $backup = $this->dbutil->backup($config);
        $nama_database = 'backup_db_hris_'.gmdate('Ymdhis', time()+60*60*7).'.zip';
        $this->load->helper('download');
        force_download($nama_database, $backup);
	}

	function json_main_menu()
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
		if( ! write_file('./src/json/main_menu.json', json_encode($json_file))){
			$notif = '<script>alert("Failed : Unable to write the file");</script>';
		}else{
			$notif = '<script>alert("File written success !");</script>';
		}
		echo $notif;
		redirect('admin/backup_db', 'refresh');
	}

	function json_sub_menu()
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
		if( ! write_file('./src/json/sub_menu.json', json_encode($json_file))){
			$notif = '<script>alert("Failed : Unable to write the file");</script>';
		}else{
			$notif = '<script>alert("File written success !");</script>';
		}
		echo $notif;
		redirect('admin/backup_db', 'refresh');
	}

    function json_menu_sidebar()
    {
        $LevelUser = $this->session->userdata('sess_level');
        $file_json = file_get_contents('./src/json/main_menu.json');
        $data_json = json_decode($file_json,true);
        // print_r($data_json);
        foreach ($data_json as $key => $value) {
        	echo $data_json[$key]['icon']." ";
        }
        // echo $data_json['1']['id_menu'];
    }

    function menu_sidebar()
    {
    	$this->l_skin->menu_sidebar();
    }

}