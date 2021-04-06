<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approve extends CI_Controller {

	// public $dir_v = 'home/approve/';
	// public $dir_m = 'home/';
	// public $dir_l = 'home/';

    public function __construct(){
        parent::__construct();
        // $this->load->library('l_auth');
    }

    public function index()
	{
		echo 'Hello Index';
	}

	public function approve($token)
	{
		// echo 'Hello approve '.$token;
		$get_attach = $this->db->query("SELECT id_surat_masuk FROM app_surat_masuk WHERE token='".$token."' LIMIT 1");
        $rows = $get_attach->row();
        $update['status'] = 7;
        $this->db->where('id_surat_masuk', $rows->id_surat_masuk);
        $this->db->update('app_surat_masuk', $update);
        echo 'Approved';
	}

	public function cancel($token)
	{
		echo 'Hello cancel '.$token;
	}

}