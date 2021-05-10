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
		// $data['head_title'] = '';
		// $data['head_subtitle'] = '';
		
		$data['icon_data'] = array(
			'Penanda Tangan'					=> array(
													'link' => site_url().'admin/signer',
													'icon' => 'img/signing_doc.png'
												),
			'Surat Keluar' 					=> array(
													'link' => site_url().'admin/surat_keluar/index',
													'icon' => 'img/icon_surat_keluar.png'
												),
			
			// 'Performance Management'		=> array(
			// 										'link' => '#',
			// 										'icon' => 'img/noimage.png'
			// 									),
			// 'Benefits Administration'		=> array(
			// 										'link' => '#',
			// 										'icon' => 'img/noimage.png'
			// 									),
			// 'Workforce Management'			=> array(
			// 										'link' => '#',
			// 										'icon' => 'img/noimage.png'
			// 									),
			// 'Time And Attendance'			=> array(
			// 										'link' => '#',
			// 										'icon' => 'img/noimage.png'
			// 									),
			// 'Absence And Leave Management'	=> array(
			// 										'link' => '#',
			// 										'icon' => 'img/noimage.png'
			// 									),
			// 'Learning And Development'		=> array(
			// 										'link' => '#',
			// 										'icon' => 'img/noimage.png'
			// 									),
			// 'Talent Management'				=> array(
			// 										'link' => '#',
			// 										'icon' => 'img/noimage.png'
			// 									),
			// 'HR Analytics'					=> array(
			// 										'link' => '#',
			// 										'icon' => 'img/noimage.png'
			// 									)
		);

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

		$this->l_skin->main('home/dashboard', $data);
	}

}