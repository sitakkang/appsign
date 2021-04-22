<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_url extends CI_Controller {

    public $dir_v = 'admin/api_url/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_superadmin();
        $this->load->model($this->dir_m.'m_api_url');
        $this->load->library($this->dir_l.'l_api_url');
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
            'src/js/admin/api_url.js');
        $data['panel'] = '<i class="fa fa-link"></i> &nbsp;<b>API URL</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    public function table()
    {
        $get_all = $this->db->query('SELECT * FROM tbl_api_action ORDER BY id DESC');

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = array();
        $i = 1;
        foreach($get_all->result() as $id) {
            $data[] = array(
                "DT_RowId" => $id->id,
                '0' => $i++,
                '1' => $id->api_key,
                '2' => $id->url,
                '3' => $this->m_api_url->type_api($id->type),
                '4' => $this->m_api_url->api_act_btn($id->id,$id->api_key,$this->m_api_url->type_api($id->type)),
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

    // ACTION POST
    function act_add()
    {
        $this->form_validation->set_rules('api_key', 'Api_key', 'trim|required');
        $this->form_validation->set_rules('url', 'URL', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        if ($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $data = array(
                    'api_key' => $this->input->post('api_key'),
                    'url' => $this->input->post('url'),
                    'type' => $this->input->post('type'),
                );
            $this->db->insert('tbl_api_action', $data);
            $notif['lastid'] = $this->db->insert_id();
            $notif['notif'] = 'Data API URL '.$this->input->post('api_key').' berhasil disimpan !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function edit($id)
    {
        $query = $this->db->query('SELECT * FROM tbl_api_action WHERE id='.$id.' LIMIT 1');
        $data['id'] = $query->row();
        $this->load->view($this->dir_v.'edit',$data);
    }

    function act_edit()
    {
        $this->form_validation->set_rules('api_key', 'Api_key', 'trim|required');
        $this->form_validation->set_rules('url', 'URL', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $data = array(
                'api_key' => $this->input->post('api_key'),
                'url' => $this->input->post('url'),
                'type' => $this->input->post('type'),
            );
            $id_ = $this->input->post("id");
            $this->db->where('id', $id_);
            $this->db->update('tbl_api_action', $data);
            $notif['notif'] = 'Perubahan api_key '.$this->input->post('api_key').' berhasil disimpan !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function act_del()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('tbl_api_action');
        $notif['notif'] = 'Data Api URL '.$this->input->post('api_key').' dengan type '. $this->input->post('type') .' berhasil di hapus !';
        $notif['status'] = 2;
        echo json_encode($notif);
    }
}