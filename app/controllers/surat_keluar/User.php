<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public $dir_v = 'surat_keluar/user/';
	public $dir_m = 'surat_keluar/';
	public $dir_l = 'surat_keluar/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->load->model($this->dir_m.'m_model');
        $this->load->library($this->dir_l.'l_libraries');
    }

    function index()
    {
        $data['css'] = array();
        $data['js'] = array();
        $data['panel'] = '<i class="fa fa-laptop"></i> &nbsp;<b>Title Panel</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }
}