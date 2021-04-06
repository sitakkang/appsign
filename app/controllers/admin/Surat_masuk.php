<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_masuk extends CI_Controller {

    public $dir_v = 'admin/surat_masuk/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
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
            'src/js/admin/surat_masuk.js');
        $data['panel'] = '<i class="fa fa-inbox"></i> &nbsp;<b>Surat Masuk</b>';
        $this->l_skin->config($this->dir_v.'view', $data);
    }

    // function sign_pdf()
    // {
    //     $data['css'] = array(
    //         'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css',
    //         'src/css/style.css');
    //     $data['js'] = array(
    //         'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js',
    //         'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js',
    //         'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js',
    //         'https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.2.9/interact.min.js',
    //         'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.worker.min.js',
    //         'src/js/admin/surat_masuk_sign.js');
    //     $data['panel'] = '<i class="fa fa-inbox"></i> &nbsp;<b>PDF</b>';
    //     $this->l_skin->config($this->dir_v.'pdf_signing', $data);
    // }

    function act_sign_doc(){
        $id_surat = $this->input->post("id");
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
            $this->db->where('id_surat_masuk', $id_surat);
            $this->db->update('app_surat_masuk', $data);
            $notif['notif'] = 'Set Posisi Berhasil !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }  
    }

    function sign_pdf($id)
    {
        $query = $this->db->query('SELECT * FROM app_surat_masuk WHERE id_surat_masuk='.$id.' LIMIT 1');
        $rows = $query->row();
        $data['id']=$id;
        $data['css'] = array(
            'src/css/style.css');
        $data['js'] = array(
            'lib/jquery/jquery-3.3.1.min.js',
            'lib/bootstrap-4.1.3/dist/js/bootstrap.min.js',
            'lib/pdfjs/pdf.js',
            'lib/interact/interact.min.js',
            'src/js/admin/app.js',
            'src/js/admin/pdf.config.js',
            'src/js/admin/signature.config.js');
        $data['panel'] = '<i class="fa fa-inbox"></i> &nbsp;<b>PDF</b>';
        $this->l_skin->config($this->dir_v.'pdf_signing', $data);
    }

    public function table()
    {
        $get_all = $this->db->query('SELECT id_surat_masuk, jenis, no_surat, perihal, pengirim, ditujukan, tgl_terima, status, attach1, attach2, attach3, attach4, attach5, attach6, attach7, attach8, attach9, attach10, posisi, document_id, approval_status FROM app_surat_masuk ORDER BY id_surat_masuk DESC');

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = array();
        $i = 1;
        foreach($get_all->result() as $id) {
            $data[] = array(
                "DT_RowId" => $id->id_surat_masuk,
                '0' => $i++,
                '1' => $this->m_surat_masuk->attachment(array($id->attach1, $id->attach2, $id->attach3, $id->attach4, $id->attach5, $id->attach6, $id->attach7, $id->attach8, $id->attach9, $id->attach10)),
                '2' => $this->m_surat_masuk->masuk_act_btn($id->id_surat_masuk, $id->no_surat),
                '3' => $this->m_surat_masuk->label_status_masuk($id->status, $id->id_surat_masuk),
                '4' => $id->no_surat,
                '5' => $id->tgl_terima,
                '6' => $id->pengirim,
                '7' => $id->jenis,
                '8' => $this->m_surat_masuk->penerima_surat($id->ditujukan),
                '9' => $id->perihal,
                '10' => $id->document_id
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

    function posisi_sign($id)
    {
        $data['id_surat'] = $id;
        $this->load->view($this->dir_v.'posisi', $data);
    }

    function act_set_posisi()
    {
        $data['posisi'] = $this->input->post("posisi");
        $data['status'] = 2;
        $id_surat = $this->input->post("id_surat");
        $this->db->where('id_surat_masuk', $id_surat);
        $this->db->update('app_surat_masuk', $data);
        $notif['notif'] = 'Perubahan Posisi berhasil !';
        $notif['status'] = 2;
        echo json_encode($notif);
    }

    function send_mail($id)
    {
        $data['id_surat'] = $id;
        $this->load->view($this->dir_v.'send', $data);
    }

    function get_ditujukan($id)
    {
        $query = $this->db->query('SELECT ditujukan FROM app_surat_masuk WHERE id_surat_masuk='.$id.' LIMIT 1');
        $rows = $query->row();
        if(empty($rows->ditujukan)){
            return NULL;
        }else{
            return $rows->ditujukan;
        }
    }


    function edit($id)
    {
        $query = $this->db->query('SELECT id_surat_masuk, no_surat, perihal, jenis, pengirim, penerima, melalui, tgl_terima, catatan FROM app_surat_masuk WHERE id_surat_masuk='.$id.' LIMIT 1');
        $data['id'] = $query->row();
        $this->load->view($this->dir_v.'edit', $data);
    }

    function act_edit()
    {
        $this->form_validation->set_rules('no_surat', 'No Surat', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('pengirim', 'Pengirim', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('penerima', 'Penerima', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tgl_terima', 'Tanggal Terima', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $data = array(
                'no_surat' => $this->input->post('no_surat'),
                'perihal' => $this->input->post('perihal'),
                'jenis' => $this->input->post('jenis'),
                'pengirim' => $this->input->post('pengirim'),
                'penerima' => $this->input->post('penerima'),
                'tgl_terima' => $this->input->post('tgl_terima'),
                'melalui' => $this->input->post('melalui'),
                'catatan' => $this->input->post('catatan')
            );
            $id_surat = $this->input->post("id_surat");
            $this->db->where('id_surat_masuk', $id_surat);
            $this->db->update('app_surat_masuk', $data);
            $notif['notif'] = 'Perubahan nomor surat '.$this->input->post('no_surat').' berhasil disimpan !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function add()
    {
        $this->load->view($this->dir_v.'add');
    }

    function act_add()
    {
        $this->form_validation->set_rules('no_surat', 'No Surat', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('pengirim', 'Pengirim', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('penerima', 'Penerima', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tgl_terima', 'Tanggal Terima', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $data = array(
                'no_surat' => $this->input->post('no_surat'),
                'perihal' => $this->input->post('perihal'),
                'jenis' => $this->input->post('jenis'),
                'pengirim' => $this->input->post('pengirim'),
                'penerima' => $this->input->post('penerima'),
                'tgl_terima' => $this->input->post('tgl_terima'),
                'melalui' => $this->input->post('melalui'),
                'catatan' => $this->input->post('catatan'),
                'flag' => 0
            );
            $this->db->insert('app_surat_masuk', $data);
            $notif['notif'] = 'Data surat '.$this->input->post('no_surat').' berhasil disimpan !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function act_del()
    {
        $id = $this->input->post('id_surat');
        $query = $this->db->query('SELECT attach1, attach2, attach3, attach4, attach5, attach6, attach7, attach8, attach9, attach10 FROM app_surat_masuk WHERE id_surat_masuk='.$id.' LIMIT 1');
        $row = $query->row_array();

        for ($x = 1; $x <= 10; $x++) {
            $link = $row["attach$x"];
            if(isset($link)){
                $old_pic = './upload/masuk/'.$link;
                unlink($old_pic);
            }
        }

        $this->db->where('id_surat_masuk', $id);
        $this->db->delete('app_surat_masuk');
        $notif['notif'] = 'Data surat berhasil di hapus !';
        $notif['status'] = 2;
        echo json_encode($notif);
    }

    function view_status($id)
    {
        $query = $this->db->query('SELECT id_surat_masuk, status FROM app_surat_masuk WHERE id_surat_masuk='.$id.' LIMIT 1');
        $data['id'] = $query->row();
        $this->load->view($this->dir_v.'status',$data);
    }

    function act_status()
    {
        $data['status'] = $this->input->post("status");
        $id_surat = $this->input->post("id_surat");
        $this->db->where('id_surat_masuk', $id_surat);
        $this->db->update('app_surat_masuk', $data);
        $notif['notif'] = 'Perubahan status berhasil !';
        $notif['status'] = 2;
        echo json_encode($notif);
    }

    function upload_attach($id)
    {
        $data['id_surat'] = $id;
        $this->load->view($this->dir_v.'upload',$data);
    }

    function sign_document($id)
    {
        $data['id_surat'] = $id;
        $this->load->view($this->dir_v.'sign', $data);
    }

    function upload_vendor($id)
    {
        $data['id_surat'] = $id;
        $this->load->view($this->dir_v.'vendor', $data);
    }

    function upload_vendor_act_btn(){
        $id_surat = $this->input->post("id_surat");
        $get_attach = $this->db->query('SELECT path_folder, llx, lly, urx, ury, attach1 FROM app_surat_masuk WHERE id_surat_masuk='.$id_surat.' LIMIT 1');
        $rows = $get_attach->row();
        $doc_id = rand(0,9999).time();
        $real_t_file = $rows->path_folder;
        $llx = $rows->llx;
        $lly = $rows->lly;
        $urx = $rows->urx;
        $ury = $rows->ury;
        $options_at = [
            'jsonfield' => json_encode(
                [
                    'JSONFile' => [
                        "userid"=> "adminimip@tandatanganku.com", 
                        "document_id" => $doc_id, 
                        "payment" => "3", 
                        "redirect" => true, 
                        "sequence_option"=>false,
                        "send-to" => array([
                            "name"=> "patar", 
                            "email"=> "adminimip@tandatanganku.com"
                        ]),
                        "req-sign" => array([
                            "name" =>"test1", 
                            "email"=>"adminimip@tandatanganku.com", 
                            "aksi_ttd"=>"at", 
                            "kuser"=>'e6aWrPrHidjmjdA1',
                            "user"=>"ttd1", 
                            "page"=>"1", 
                            "llx"=>"30", 
                            "lly"=>"30", 
                            "urx"=>"30", 
                            "ury"=>"30", 
                            "visible"=>"1"])
                    ],
                ],
            ),
            'file'=>new \CurlFile(
                        $real_t_file,
                        'pdf',
                        'test'
                    ),
        ];
        $options_mt = [
            'jsonfield' => json_encode(
                [
                    'JSONFile' => [
                        "userid"=> "adminimip@tandatanganku.com", 
                        "document_id" => $doc_id, 
                        "payment" => "3", 
                        "redirect" => true, 
                        "sequence_option"=>false,
                        "send-to" => array([
                            "name"=> "patar", 
                            "email"=> "adminimip@tandatanganku.com"
                        ]),
                        "req-sign" => array([
                            "name" =>"test1", 
                            "email"=>"adminimip@tandatanganku.com", 
                            "aksi_ttd"=>"mt", 
                            "user"=>"ttd1", 
                            "page"=>"1", 
                            "llx"=>$llx, 
                            "lly"=>$lly, 
                            "urx"=>$urx, 
                            "ury"=>$ury, 
                            "visible"=>"1"])
                    ],
                ],
            ),
            'file'=>new \CurlFile(
                        $real_t_file,
                        'pdf',
                        'test'
                    ),
        ];
        $headers=array('Authorization: Bearer gLgyVTNNZrEJPIiu1VUCOMpR16xdGa9aeuk5cVeN44vQOpi8VTkAZQwiqtz3EM');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.tandatanganku.com/SendDocMitraAT.html");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $options_mt);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
        $response = curl_exec($curl);
        $response_text = json_decode($response);
        if(isset($response_text->JSONFile->result)){
           if($response_text->JSONFile->result!='00'){
                $notif['notif'] = 'Terjadi kesalahan saat upload file ke server Digisign !';
                $notif['status'] = 1;
            }else{
                $update['document_id'] = $doc_id;
                $update['status'] = 4;
                $this->db->where('id_surat_masuk', $id_surat);
                $this->db->update('app_surat_masuk', $update);
                $notif['notif'] = 'Upload file ke server Digisign Berhasil!';
                $notif['status'] = 2;
            } 
        }else{
            $notif['notif'] = 'Api Connection Error !';
            $notif['status'] = 1;
        }

        echo json_encode($notif);
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
            $targetPath = './upload/masuk/'.$tahun.'/'.$bulan.'/';
            $targetFile = $targetPath.$fileName;
            if(!file_exists($targetPath)){mkdir($targetPath, 0777, true);}
            $linkAttach = $tahun.'/'.$bulan.'/'.$fileName;
            if(!empty($this->cek_file_attach($id_surat))){
                if(move_uploaded_file($tempFile, $targetFile)){
                    $data_update = $this->cek_file_attach($id_surat);
                    $update[$data_update] = $linkAttach;
                    $t_file='upload/masuk/'.$tahun.'/'.$bulan.'/'.$fileName;
                    $real_t_file=realpath(APPPATH . '../' . $t_file);
                    $update['status'] = 1;
                    $update['path_folder'] = $real_t_file;
                    $this->db->where('id_surat_masuk', $id_surat);
                    $this->db->update('app_surat_masuk', $update);
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

    function send_mail_smtp($token){
        $data=[];
        $this->load->library('email');
        $config = array(
                'protocol'  => 'smtp', 
                'smtp_host' => 'mail.imip.co.id', 
                'smtp_user' => 'patar@imip.co.id', 
                'smtp_pass' => 'Q92dvh#8', 
                'smtp_port' => '587', 
                'mail_type' => 'html', 
                'charset'   => 'iso-8859-1', 
        );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from('patar@imip.co.id','Approval Digital Sign');
        $this->email->to('adminimip@tandatanganku.com'); 
        $this->email->subject('Test Mail API');
        // $this->email->message('Test API');  
        // $this->email->message('Approval Link http://localhost/appsign/approve/approve/'.$token.' </b> to cancel http://localhost/appsign/approve/cancel/'. $token .' </b>');
        $mailContent="";
        $mailContent.="Approval Link http://localhost/appsign/approve/approve/".$token."\r\n";
        $mailContent.="Cancel Link http://localhost/appsign/approve/cancel/".$token."\r\n";
        $this->email->message($mailContent);
        // $this->email->message('Test API');  
        // $this->load->view($this->dir_v.'posisi', $data);
        // $mailContent    = $this->load->view($this->dir_v.'v_email', $data, TRUE);
        // $this->email->Body($mailContent);
        if($this->email->send()){
            return true;
        }else{
            return false;
        }
    }

    function act_sign_document()
    {
        if($this->input->post('ditujukan') === '""'){
            $notif['notif'] = 'Penanda tangan tidak boleh kosong !';
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $id_surat = $this->input->post('id_surat');
            $query = $this->db->query('SELECT id_surat_masuk, document_id FROM app_surat_masuk WHERE id_surat_masuk='.$id_surat.' LIMIT 1');
            $data = $query->row();
            $doc_id=$data->document_id;
            $options = [
                'jsonfield' => json_encode(
                    [
                        'JSONFile' => [
                            "userid"=> "adminimip@tandatanganku.com", 
                            "document_id" => $doc_id, 
                            "email_user" =>"adminimip@tandatanganku.com", 
                            "view_only" => false
                        ],
                    ],
                )
            ];
            $headers=array('Authorization: Bearer gLgyVTNNZrEJPIiu1VUCOMpR16xdGa9aeuk5cVeN44vQOpi8VTkAZQwiqtz3EM');
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://api.tandatanganku.com/gen/genSignPage.html");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_VERBOSE, 1);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $options);
            curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
            $response = curl_exec($curl);
            $response_text = json_decode($response);
            // echo "<pre>";
            // print_r($response_text);
            // echo "</pre>";
            // die();
            if($response_text->JSONFile->result!='00'){
                $notif['notif'] = 'Error : '. $response_text->JSONFile->notif;
                $notif['status'] = 1;
                echo json_encode($notif);
            }else{
                $update['link_document'] = $response_text->JSONFile->link;
                $this->db->where('id_surat_masuk', $id_surat);
                $this->db->update('app_surat_masuk', $update);
                $notif['notif'] = 'Document Signing Succeed';
                $notif['status'] = 2;
                $notif['id'] = $id_surat;
                $notif['url_api']=$response_text->JSONFile->link;
                echo json_encode($notif);

                
            }

        }
    }

    function proses($id)
    {
        $query = $this->db->query('SELECT id_surat_masuk, document_id, link_document FROM app_surat_masuk WHERE id_surat_masuk='.$id.' LIMIT 1');
            $data = $query->row();
        $data=[];
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css'
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'src/js/admin/surat_masuk.js'
        );
        
        $data['panel'] = '<i class="fa fa-items"></i> &nbsp;<b>Proses Sign</b>';
        $this->l_skin->config($this->dir_v.'view_proses',$data);
    }

    function generate_token(){
        $digits = '';
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        for($i=0;$i<7; $i++){
            $x = mt_rand(0, strlen($chars) -1);
            $digits .= $chars[$x];
        }
        $token=$digits;
        return $token;
    }

    function act_send_mail()
    {
        if($this->input->post('ditujukan') === '""'){
            $notif['notif'] = 'Penerima surat tidak boleh kosong !';
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $id_surat = $this->input->post('id_surat');
            if($this->cek_empty_attach($id_surat)){
                $ditujukan = $this->l_surat_masuk->FilterArray($this->input->post('ditujukan'));
                $token=$this->generate_token();
                $update['ditujukan'] = $ditujukan;
                $update['status'] = 5;
                $update['approval_status'] = 1;
                $update['token'] = $token;
                $send_mail_smtp=$this->send_mail_smtp($token);

                if($send_mail_smtp){
                    $this->db->where('id_surat_masuk', $id_surat);
                    $this->db->update('app_surat_masuk', $update);
                    $notif['notif'] = 'Data surat berhasil di kirim !';
                    $notif['status'] = 2;
                    echo json_encode($notif);
                }else{
                    $notif['notif'] = 'Pengiriman Surat Gagal';
                    $notif['status'] = 1;
                    echo json_encode($notif);
                }
                
            }else{
                $notif['notif'] = 'Attachment surat belum di upload !';
                $notif['status'] = 1;
                echo json_encode($notif);
            }
        }
    }

    function cek_empty_attach($id)
    {
        $get_attach = $this->db->query('SELECT attach1, attach2, attach3 FROM app_surat_masuk WHERE id_surat_masuk='.$id.' LIMIT 1');
        $rows = $get_attach->row();
        if(!empty($rows->attach1) OR !empty($rows->attach2) OR !empty($rows->attach3)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function cek_file_attach($id)
    {
        $get_attach = $this->db->query('SELECT attach1, attach2, attach3, attach4, attach5, attach6, attach7, attach8, attach9, attach10 FROM app_surat_masuk WHERE id_surat_masuk='.$id.' LIMIT 1');
        $rows = $get_attach->row();

        if(empty($rows->attach1)){
            return 'attach1';
        }
        else{
            return '';
        }
    }
}