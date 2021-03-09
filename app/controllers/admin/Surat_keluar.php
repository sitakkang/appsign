<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_keluar extends CI_Controller {

    public $dir_v = 'admin/surat_keluar/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->load->model($this->dir_m.'m_surat_keluar');
        $this->load->model($this->dir_m.'m_surat_masuk');
        $this->load->library($this->dir_l.'l_surat_masuk');
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
            'src/js/admin/surat_keluar.js');
        $data['panel'] = '<i class="fa fa-envelope-open-text"></i> &nbsp;<b>Surat Keluar</b>';
        $this->l_skin->config($this->dir_v.'view', $data);
    }

    public function table()
    {
        $get_all = $this->db->query('SELECT id_surat_keluar, jenis, no_surat, perihal, diusulkan, disetujui, tgl_kirim, status, attach1, attach2, attach3, attach4, attach5, attach6, attach7, attach8, attach9, attach10 FROM app_surat_keluar ORDER BY id_surat_keluar DESC');

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = array();
        $i = 1;
        foreach($get_all->result() as $id) {
            $data[] = array(
                "DT_RowId" => $id->id_surat_keluar,
                '0' => $i++,
                '1' => $this->m_surat_keluar->attachment(array($id->attach1, $id->attach2, $id->attach3, $id->attach4, $id->attach5, $id->attach6, $id->attach7, $id->attach8, $id->attach9, $id->attach10)),
                '2' => $this->m_surat_keluar->keluar_act_btn($id->id_surat_keluar, $id->no_surat),
                '3' => $this->m_surat_keluar->label_status_keluar($id->status, $id->id_surat_keluar),
                '4' => $id->no_surat,
                '5' => $id->tgl_kirim,
                '6' => $id->diusulkan,
                '7' => $id->jenis,
                '8' => $this->m_surat_masuk->penerima_surat($id->disetujui),
                '9' => $id->perihal
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

    function act_add()
    {
        $this->form_validation->set_rules('no_surat', 'No Surat', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tujuan', 'Ditujukan', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('diusulkan', 'Diusulkan', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tgl_kirim', 'Tanggal Kirim', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $data = array(
                'no_surat' => $this->input->post('no_surat'),
                'perihal' => $this->input->post('perihal'),
                'jenis' => $this->input->post('jenis'),
                'tujuan' => $this->input->post('tujuan'),
                'diusulkan' => $this->input->post('diusulkan'),
                'tgl_kirim' => $this->input->post('tgl_kirim'),
                'melalui' => $this->input->post('melalui'),
                'catatan' => $this->input->post('catatan'),
                'flag' => 0
            );
            $this->db->insert('app_surat_keluar', $data);
            $notif['notif'] = 'Data surat '.$this->input->post('no_surat').' berhasil disimpan !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function edit($id)
    {
        $query = $this->db->query('SELECT id_surat_keluar, no_surat, perihal, jenis, diusulkan, tujuan, melalui, tgl_kirim, catatan FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $data['id'] = $query->row();
        $this->load->view($this->dir_v.'edit',$data);
    }

    function act_edit()
    {
        $this->form_validation->set_rules('no_surat', 'No Surat', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('diusulkan', 'Diusulkan oleh', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tujuan', 'Ditujukan ke', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tgl_kirim', 'Tanggal Kirim', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $data = array(
                'no_surat' => $this->input->post('no_surat'),
                'perihal' => $this->input->post('perihal'),
                'jenis' => $this->input->post('jenis'),
                'tujuan' => $this->input->post('tujuan'),
                'diusulkan' => $this->input->post('diusulkan'),
                'tgl_kirim' => $this->input->post('tgl_kirim'),
                'melalui' => $this->input->post('melalui'),
                'catatan' => $this->input->post('catatan')
            );
            $id_surat = $this->input->post("id_surat");
            $this->db->where('id_surat_keluar', $id_surat);
            $this->db->update('app_surat_keluar', $data);
            $notif['notif'] = 'Perubahan surat '.$this->input->post('no_surat').' berhasil disimpan !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function act_del()
    {
        $id = $this->input->post('id_surat');
        $query = $this->db->query('SELECT attach1, attach2, attach3, attach4, attach5, attach6, attach7, attach8, attach9, attach10 FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $row = $query->row_array();

        for ($x = 1; $x <= 10; $x++) {
            $link = $row["attach$x"];
            if(isset($link)){
                $old_pic = './upload/keluar/'.$link;
                unlink($old_pic);
            }
        }

        $this->db->where('id_surat_keluar', $id);
        $this->db->delete('app_surat_keluar');
        $notif['notif'] = 'Data surat berhasil di hapus !';
        $notif['status'] = 2;
        echo json_encode($notif);
    }

    function view_status($id)
    {
        $query = $this->db->query('SELECT id_surat_keluar, status FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $data['id'] = $query->row();
        $this->load->view($this->dir_v.'status', $data);
    }

    function act_status()
    {
        $data['status'] = $this->input->post("status");
        $id_surat = $this->input->post("id_surat");
        $this->db->where('id_surat_keluar', $id_surat);
        $this->db->update('app_surat_keluar', $data);
        $notif['notif'] = 'Perubahan status berhasil !';
        $notif['status'] = 2;
        echo json_encode($notif);
    }

    function upload_attach($id)
    {
        $data['id_surat'] = $id;
        $this->load->view($this->dir_v.'upload', $data);
    }

    function act_upload_attach()
    {
        if(!empty($_FILES)){
            $id_surat = $this->input->post('id_surat');
            $tempFile = $_FILES['file']['tmp_name'];
            $tempName = $_FILES['file']['name'];
            $tempExt = pathinfo($tempName, PATHINFO_EXTENSION);
            $fileName = $this->l_surat_masuk->RandStr2(10).".".$tempExt;
            $tahun = $this->l_surat_masuk->DateYear();
            $bulan = $this->l_surat_masuk->DateMonth();
            $targetPath = './upload/keluar/'.$tahun.'/'.$bulan.'/';
            $targetFile = $targetPath.$fileName;
            if(!file_exists($targetPath)){mkdir($targetPath, 0777, true);}
            $linkAttach = $tahun.'/'.$bulan.'/'.$fileName;
            if(!empty($this->cek_file_attach($id_surat))){
                if(move_uploaded_file($tempFile, $targetFile)){
                    $data_update = $this->cek_file_attach($id_surat);
                    $update[$data_update] = $linkAttach;
                    $this->db->where('id_surat_keluar', $id_surat);
                    $this->db->update('app_surat_keluar', $update);
                }else{
                    header("HTTP/1.0 400 Bad Request");
                    echo 'Terjadi kesalahan saat upload file ke server !';
                }
            }else{
                header("HTTP/1.0 400 Bad Request");
                echo 'Attachment file sudah penuh !';
            }
        }else{
            header("HTTP/1.0 400 Bad Request");
            echo 'Terjadi kesalahan saat upload file ke server !';
        }
    }

    function cek_file_attach($id)
    {
        $get_attach = $this->db->query('SELECT attach1, attach2, attach3, attach4, attach5, attach6, attach7, attach8, attach9, attach10 FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $rows = $get_attach->row();

        if(empty($rows->attach1)){
            return 'attach1';
        }elseif(empty($rows->attach2)){
            return 'attach2';
        }elseif(empty($rows->attach3)){
            return 'attach3';
        }elseif(empty($rows->attach4)){
            return 'attach4';
        }elseif(empty($rows->attach5)){
            return 'attach5';
        }elseif(empty($rows->attach6)){
            return 'attach6';
        }elseif(empty($rows->attach7)){
            return 'attach7';
        }elseif(empty($rows->attach8)){
            return 'attach8';
        }elseif(empty($rows->attach9)){
            return 'attach9';
        }elseif(empty($rows->attach10)){
            return 'attach10';
        }else{
            return '';
        }
    }

    function send_mail($id)
    {
        $data['id_surat'] = $id;
        $data['tujuan'] = $this->get_disetujui($id);
        $this->load->view($this->dir_v.'send', $data);
    }

    function act_send_mail()
    {
        if($this->input->post('disetujui') === '""'){
            $notif['notif'] = 'Data surat belum disetujui oleh siapapun !';
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $id_surat = $this->input->post('id_surat');
            if($this->cek_empty_attach($id_surat)){
                $disetujui = $this->lib_core->FilterArray($this->input->post('disetujui'));
                $update['disetujui'] = $disetujui;
                $update['status'] = 1;
                $this->db->where('id_surat_keluar', $id_surat);
                $this->db->update('app_surat_keluar', $update);
                $notif['notif'] = 'Data surat berhasil di update !';
                $notif['status'] = 2;
                echo json_encode($notif);
            }else{
                $notif['notif'] = 'Attachment surat belum di upload !';
                $notif['status'] = 1;
                echo json_encode($notif);
            }
        }
    }

    function cek_empty_attach($id)
    {
        $get_attach = $this->db->query('SELECT attach1, attach2, attach3 FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $rows = $get_attach->row();
        if(!empty($rows->attach1) OR !empty($rows->attach2) OR !empty($rows->attach3)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function get_disetujui($id)
    {
        $query = $this->db->query('SELECT disetujui FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $rows = $query->row();
        if(empty($rows->disetujui)){
            return NULL;
        }else{
            return $rows->disetujui;
        }
    }
}