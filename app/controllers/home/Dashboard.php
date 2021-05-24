<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->m_auth->check_login();
		// $this->m_auth->check_superadmin();
	}

	function index()
	{
		$data['icon_data'] = array(
			'Penanda Tangan'					=> array(
													'link_checking'=>'admin/signer',
													'link' => site_url().'admin/signer',
													'icon' => 'img/signing_doc.png'
												),
			'Surat Keluar Admin' 					=> array(
													'link_checking'=>'surat_keluar/surat_keluar_admin',
													'link' => site_url().'surat_keluar/surat_keluar_admin',
													'icon' => 'img/icon_surat_keluar.png'
												),
			'Surat Keluar User' 					=> array(
													'link_checking'=>'surat_keluar/surat_keluar',
													'link' => site_url().'surat_keluar/surat_keluar',
													'icon' => 'img/icon_surat_keluar.png'
												),
		);
		if($this->session->userdata('sess_level') == 1){
			$data['sidebar_data'] = array(
				'API Type' 			=> array(
												'link' => site_url().'admin/api_type',
												'icon' => ''
											),
				'API URL'				=> array(
												'link' => site_url().'admin/api_url',
												'icon' => ''
											),
				
			);
		}else{
			$data['sidebar_data'] = array(
				'Home' 			=> array(
												'link' => site_url().'home',
												'icon' => ''
											)
				
			);
		}
		$this->l_skin->main('home/dashboard', $data);
	}

}