<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_type extends CI_Controller {

    public $dir_v = 'admin/api_type/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_superadmin();
        $this->load->model($this->dir_m.'m_api_type');
        $this->load->library($this->dir_l.'l_api_type');
    }

    function index()
    {
        $data['css'] = array(
            'lib/ext/popup/magnific-popup.css',
            'lib/ext/dropzone/dropzone.min.css',
            'lib/ext/select/select.min.css',
            'lib/ext/datepicker/datepicker.min.css',
            'lib/datatables/dataTables.bootstrap.min.css');
        $data['js'] = array(
            'lib/ext/popup/jquery.magnific-popup.min.js',
            'lib/ext/zooming/wheelzoom.js',
            'lib/ext/pdfobject/pdfobject.min.js',
            'lib/ext/dropzone/dropzone.min.js',
            'lib/ext/select/select.min.js',
            'lib/ext/datepicker/datepicker.min.js',
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'src/js/admin/api_type.js'
        );
        $data['panel'] = '<i class="fa fa-check-double"></i> &nbsp;<b>API Type</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    public function table()
    {
        $get_all = $this->db->query('SELECT id, type, status FROM api_activation ORDER BY id DESC');

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = array();
        $i = 1;
        foreach($get_all->result() as $id) {
            $data[] = array(
                "DT_RowId" => $id->id,
                '0' => $i++,
                '1' => $id->type,
                '2' => $id->status,
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

    public function api_setup(){
        $this->load->view($this->dir_v.'setup');
    }

    public function act_api_setup(){
        $datafirst = array(
            'status' => 'not active',
        );
        $id_ = $this->input->post("id");
        $this->db->where('status', 'active');
        $this->db->update('api_activation', $datafirst);

        $datasecond = array(
            'status' => 'active',
        );
        $this->db->where('id', $id_);
        $this->db->update('api_activation', $datasecond);
        $notif['notif'] = 'Setup Api Telah berubah !';
        $notif['status'] = 2;
        echo json_encode($notif);
    }
}