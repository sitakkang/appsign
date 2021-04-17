<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signer extends CI_Controller {

    public $dir_v = 'admin/signer/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->load->model($this->dir_m.'m_signer');
        $this->load->library($this->dir_l.'l_signer');
    }

    function index()
    {
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css',
            'lib/datepicker/datepicker.min.css',
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'lib/datepicker/datepicker.min.js',
            'src/js/admin/signer.js'
        );
        $data['panel'] = '<i class="fa fa-file-signature"></i> &nbsp;<b>Signer</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    function table()
    {
        $get_all = $this->db->query('SELECT a.id, a.name, a.email_user, a.email_digisign, a.id_ktp, a.id_npwp, a.jenis_kelamin, a.telepon, a.alamat, a.kode_pos, a.tempat_lahir, a.tgl_lahir, a.provinci, a.kota, a.kecamatan, a.desa, a.kuser_production, a.kuser_sandbox FROM t_signer a');

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = array();
        $i = 1;
        foreach($get_all->result() as $id) {
            $lahir=$id->tempat_lahir . ", " . $id->tgl_lahir."";
            $data[] = array(
                
                "DT_RowId" => $id->id,
                "0" => $i++,
                "1" => $id->name,
                "2" => $id->email_user,
                "3" => $id->email_digisign,
                "4" => $id->kuser_production,
                "5" => $id->kuser_sandbox,
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
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('email_user', 'Email User', 'trim|required|min_length[1]|is_unique[t_signer.email_user]');
        $this->form_validation->set_rules('email_digisign', 'Email Digisign', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('kuser_production', 'Kuser Production', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('kuser_sandbox', 'Kuser Sandbox', 'trim|required|min_length[1]');
        if ($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $data = array(
                    'name' => $this->input->post('name'),
                    'email_user' => $this->input->post('email_user'),
                    'id_ktp' => $this->input->post('id_ktp'),
                    'id_npwp' => $this->input->post('id_npwp'),
                    'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                    'telepon' => $this->input->post('telepon'),
                    'alamat' => $this->input->post('alamat'),
                    'kode_pos' => $this->input->post('kode_pos'),
                    'tempat_lahir' => $this->input->post('tempat_lahir'),
                    'tgl_lahir' => $this->input->post('tgl_lahir'),
                    'provinci' => $this->input->post('provincy'),
                    'kota' => $this->input->post('kota'),
                    'kecamatan' => $this->input->post('kecamatan'),
                    'desa' => $this->input->post('desa'),
                    'email_digisign' => $this->input->post('email_digisign'),
                    'kuser_sandbox' => $this->input->post('kuser_sandbox'),
                    'kuser_production' => $this->input->post('kuser_production'),
                );
            $this->db->insert('t_signer', $data);
            $notif['lastid'] = $this->db->insert_id();
            $notif['notif'] = 'Data Signer '.$this->input->post('name').' berhasil disimpan !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function edit()
    {
        $data_id = $this->input->get('id');
        $result_id = $this->db->query('SELECT id, name, email_user, email_digisign, kuser_production, kuser_sandbox, id_ktp, id_npwp, jenis_kelamin, alamat, telepon, provinci, kota, kecamatan, desa, tempat_lahir, tgl_lahir, kode_pos FROM t_signer WHERE id='.$data_id.' LIMIT 1');
        $data['id'] = $result_id->row();
        $this->load->view($this->dir_v.'edit', $data);
    }

    function act_edit()
    {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
       $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('email_user', 'Email User', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('email_digisign', 'Email Digisign', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('kuser_production', 'Kuser Production', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('kuser_sandbox', 'Kuser Sandbox', 'trim|required|min_length[1]');
        if ($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $data = array(
                'name' => $this->input->post('name'),
                'email_user' => $this->input->post('email_user'),
                'id_ktp' => $this->input->post('id_ktp'),
                'id_npwp' => $this->input->post('id_npwp'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'telepon' => $this->input->post('telepon'),
                'alamat' => $this->input->post('alamat'),
                'kode_pos' => $this->input->post('kode_pos'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tgl_lahir' => $this->input->post('tgl_lahir'),
                'provinci' => $this->input->post('provincy'),
                'kota' => $this->input->post('kota'),
                'kecamatan' => $this->input->post('kecamatan'),
                'desa' => $this->input->post('desa'),
                'email_digisign' => $this->input->post('email_digisign'),
                'kuser_sandbox' => $this->input->post('kuser_sandbox'),
                'kuser_production' => $this->input->post('kuser_production'),
            );
            $this->db->where('id', $id);
            $this->db->update('t_signer', $data);
            $notif['notif'] = 'Data Signer '.$this->input->post('name').' berhasil diubah !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function act_del()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->delete('t_signer');
        $notif['notif'] = 'Data Signer '.$this->input->post('name').' berhasil di hapus !';
        $notif['status'] = 2;
        echo json_encode($notif);
    }

    function getKota(){
        $data_id = $this->input->get('id');
        $message='';
        $query = $this->db->query('SELECT a.id,a.nama FROM kota a where id_prov="'. $data_id . '"');
        $message .= '<option value="" disabled selected hidden>Pilih Kota/Kabupaten</option>';
        foreach($query->result() as $id) {
            $selected="";
            if(!empty($data)){
                if(strstr($data, $id->id) != FALSE){
                    $selected="selected";
                }
            }
            $message .= '<option value="'.$id->id.'" '.$selected.'>'.$id->nama.'</option>';
        }
        $notif['kecamatan_html']='<option value="" disabled selected hidden>Pilih Kecamatan</option>';
        $notif['desa_html']='<option value="" disabled selected hidden>Pilih Desa</option>';
        $notif['html'] =$message; 
        $notif['status'] = 2;
        echo json_encode($notif);
    }

    function getKecamatan(){
        $data_id = $this->input->get('id');
        $message='';
        $query = $this->db->query('SELECT a.id,a.nama FROM kecamatan a where id_kabupaten="'. $data_id . '"');
        $message .= '<option value="" disabled selected hidden>Pilih Kecamatan</option>';
        foreach($query->result() as $id) {
            $message .= '<option value="'.$id->id.'">'.$id->nama.'</option>';
        }
        $notif['desa_html']='<option value="" disabled selected hidden>Pilih Desa</option>';
        $notif['html'] =$message; 
        $notif['status'] = 2;
        echo json_encode($notif);
    }

    function getDesa(){
        $data_id = $this->input->get('id');
        $message='';
        $query = $this->db->query('SELECT a.id,a.nama FROM desa a where id_kecamatan="'. $data_id . '"');
        $message .= '<option value="" disabled selected hidden>Pilih Desa</option>';
        foreach($query->result() as $id) {
            $message .= '<option value="'.$id->id.'">'.$id->nama.'</option>';
        }
        $notif['html'] =$message; 
        $notif['status'] = 2;
        echo json_encode($notif);
    }
}