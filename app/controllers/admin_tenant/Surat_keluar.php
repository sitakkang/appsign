<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_keluar extends CI_Controller {

    public $dir_v = 'admin_tenant/surat_keluar/';
	public $dir_m = 'admin_tenant/';
	public $dir_l = 'admin_tenant/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_not_superadmin();
        $this->load->model($this->dir_m.'m_surat_keluar');
        $this->load->library($this->dir_l.'l_surat_keluar');
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
            'src/js/admin_tenant/surat_keluar.js');
        $data['panel'] = '<i class="fa fa-envelope-open-text"></i> &nbsp;<b>Surat Keluar</b>';
        $this->l_skin->config($this->dir_v.'view', $data);
    }

    public function table()
    {
        $user_id = $this->session->userdata('sess_id');
        $get_all = $this->db->query('SELECT id_surat_keluar, file_downloaded, jenis, no_surat, perihal, diusulkan, disetujui, tgl_kirim, status, attach1, document_id, approval_status, tujuan FROM app_surat_keluar where user='. $user_id .' ORDER BY id_surat_keluar DESC');

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = array();
        $i = 1;
        foreach($get_all->result() as $id) {
            $data[] = array(
                "DT_RowId" => $id->id_surat_keluar,
                '0' => $i++,
                '1' => $this->m_surat_keluar->attachment(array($id->attach1)),
                '2' => $this->m_surat_keluar->keluar_act_btn($id->id_surat_keluar, $id->no_surat),
                '3' => $this->m_surat_keluar->label_status_keluar($id->status, $id->id_surat_keluar),
                '4' => $id->no_surat,
                '5' => $id->tgl_kirim,
                '6' => $id->diusulkan,
                '7' => $id->jenis,
                '8' => $this->m_surat_keluar->penerima_surat($id->disetujui),
                '9' => $id->perihal,
                '10' => $id->tujuan,
                '11' => $this->m_surat_keluar->attachment_downloaded(array($id->file_downloaded)),
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
        $user_id = $this->session->userdata('sess_id');
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
                'user' => $user_id,
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
            $fileName = $this->l_surat_keluar->RandStr2(10).".".$tempExt;
            $tahun = $this->l_surat_keluar->DateYear();
            $bulan = $this->l_surat_keluar->DateMonth();
            $targetPath = './upload/keluar/'.$tahun.'/'.$bulan.'/';
            $targetFile = $targetPath.$fileName;
            if(!file_exists($targetPath)){mkdir($targetPath, 0777, true);}
            $linkAttach = $tahun.'/'.$bulan.'/'.$fileName;
            if(!empty($this->cek_file_attach($id_surat))){
                if(move_uploaded_file($tempFile, $targetFile)){
                    $data_update = $this->cek_file_attach($id_surat);
                    $update[$data_update] = $linkAttach;
                    $t_file='upload/keluar/'.$tahun.'/'.$bulan.'/'.$fileName;
                    $real_t_file=realpath(APPPATH . '../' . $t_file);
                    $update['status'] = 1;
                    $update['path_folder'] = $real_t_file;
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
        $get_attach = $this->db->query('SELECT attach1 FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $rows = $get_attach->row();

        if(empty($rows->attach1)){
            return 'attach1';
        }
        else{
            return '';
        }
    }

     function sign_pdf($id)
    {
        $query = $this->db->query('SELECT * FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $rows = $query->row();
        $path_folder=$rows->path_folder;
        $b64Doc = chunk_split(base64_encode(file_get_contents($path_folder)));
        $data['id']=$id;
        $data['pdf_base64']=$b64Doc;
        $data['css'] = array(
            'src/css/style.css');
        $data['js'] = array(
            'lib/jquery/jquery-3.3.1.min.js',
            'lib/bootstrap-4.1.3/dist/js/bootstrap.min.js',
            'lib/pdfjs/pdf.js',
            'lib/interact/interact.min.js',
            'src/js/admin_tenant/app.js',
            'src/js/admin_tenant/pdf.config.js',
            'src/js/admin_tenant/signature.config.js');
        $data['path_folder']=$path_folder;
        $data['panel'] = '<i class="fa fa-inbox"></i> &nbsp;<b>PDF</b>';
        $this->l_skin->config($this->dir_v.'pdf_signing', $data);
    }

    function act_sign_doc(){
        $id_surat = $this->input->post("id");
        $query = $this->db->query('SELECT id_surat_keluar, status, no_surat, perihal, jenis, diusulkan, tujuan, melalui, tgl_kirim, catatan FROM app_surat_keluar WHERE id_surat_keluar='.$id_surat.' LIMIT 1');
        $rows = $query->row();
        $no_surat=$rows->no_surat;
        $status=$rows->status;
        if($status==1 || $status==2 || $status==4){
            $llx = $this->input->post("llx");
            $urx = $this->input->post("urx");
            $lly = $this->input->post("lly");
            $ury = $this->input->post("ury");
            $this->form_validation->set_rules('llx', 'llx Failed', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('lly', 'lly Failed', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('urx', 'urx Failed', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('ury', 'ury Failed', 'trim|required|greater_than[0]');
            if($this->form_validation->run() == FALSE){
                $notif['notif'] = validation_errors();
                $notif['status'] = 1;
                echo json_encode($notif);
            }else{
                 $data['status'] = 2;
                $data['llx'] = $llx;
                $data['lly'] = $lly;
                $data['urx'] = $urx;
                $data['ury'] = $ury;
                $this->db->where('id_surat_keluar', $id_surat);
                $this->db->update('app_surat_keluar', $data);
                $notif['notif'] = 'Set Posisi Berhasil !';
                $notif['status'] = 2;
                echo json_encode($notif);
            }  
        }else{
            $notif['notif'] = 'Sign surat '.$no_surat.' Gagal. Ijin Sign data Ditolak. !';
            $notif['status'] = 1;
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
            $id_surat = $this->input->post("id_surat");
            $query = $this->db->query('SELECT id_surat_keluar, status, no_surat, perihal, jenis, diusulkan, tujuan, melalui, tgl_kirim, catatan FROM app_surat_keluar WHERE id_surat_keluar='.$id_surat.' LIMIT 1');
            $rows = $query->row();
            $status=$rows->status;
            if($status==0 || $status==1){
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
                $this->db->where('id_surat_keluar', $id_surat);
                $this->db->update('app_surat_keluar', $data);
                $notif['notif'] = 'Perubahan surat '.$this->input->post('no_surat').' berhasil disimpan !';
                $notif['status'] = 2;
                echo json_encode($notif);
            }else{
                $notif['notif'] = 'Perubahan surat '.$this->input->post('no_surat').' Gagal. Ijin ubah data Ditolak. !';
                $notif['status'] = 1;
                echo json_encode($notif);
            }
            
        }
    }

    function act_del()
    {
        $id = $this->input->post('id_surat');
        $query = $this->db->query('SELECT attach1, status FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $row = $query->row_array();
        $rows = $query->row();
        $status=$rows->status;
        if($status==0 || $status==1){
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
        }else{
            $notif['notif'] = 'Penghapusan data surat Gagal. Ijin hapus data Ditolak. !';
            $notif['status'] = 1;
            echo json_encode($notif);
        }
    }


}