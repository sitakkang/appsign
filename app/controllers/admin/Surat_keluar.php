<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_keluar extends CI_Controller {

    public $dir_v = 'admin/surat_keluar/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_superadmin();
        $this->load->model($this->dir_m.'m_surat_keluar');
        // $this->load->model($this->dir_m.'m_surat_masuk');
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
            'src/js/admin/surat_keluar.js');
        $data['panel'] = '<i class="fa fa-envelope-open-text"></i> &nbsp;<b>Surat Keluar</b>';
        $this->l_skin->config($this->dir_v.'view', $data);
    }

    public function table()
    {
        $get_all = $this->db->query('SELECT id_surat_keluar, file_downloaded, jenis, no_surat, perihal, diusulkan, disetujui, tgl_kirim, status, attach1, document_id, approval_status, tujuan FROM app_surat_keluar ORDER BY id_surat_keluar DESC');

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
                '10' => $id->document_id,
                '11' => $id->tujuan,
                '12' => $this->m_surat_keluar->attachment_downloaded(array($id->file_downloaded)),
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

    // function add()
    // {
    //     $this->load->view($this->dir_v.'add');
    // }

    // function act_add()
    // {
    //     $this->form_validation->set_rules('no_surat', 'No Surat', 'trim|required|min_length[3]');
    //     $this->form_validation->set_rules('perihal', 'Perihal', 'trim|required|min_length[3]');
    //     $this->form_validation->set_rules('tujuan', 'Ditujukan', 'trim|required|min_length[3]');
    //     $this->form_validation->set_rules('diusulkan', 'Diusulkan', 'trim|required|min_length[3]');
    //     $this->form_validation->set_rules('tgl_kirim', 'Tanggal Kirim', 'trim|required');
    //     if($this->form_validation->run() == FALSE){
    //         $notif['notif'] = validation_errors();
    //         $notif['status'] = 1;
    //         echo json_encode($notif);
    //     }else{
    //         $data = array(
    //             'no_surat' => $this->input->post('no_surat'),
    //             'perihal' => $this->input->post('perihal'),
    //             'jenis' => $this->input->post('jenis'),
    //             'tujuan' => $this->input->post('tujuan'),
    //             'diusulkan' => $this->input->post('diusulkan'),
    //             'tgl_kirim' => $this->input->post('tgl_kirim'),
    //             'melalui' => $this->input->post('melalui'),
    //             'catatan' => $this->input->post('catatan'),
    //             'flag' => 0
    //         );
    //         $this->db->insert('app_surat_keluar', $data);
    //         $notif['notif'] = 'Data surat '.$this->input->post('no_surat').' berhasil disimpan !';
    //         $notif['status'] = 2;
    //         echo json_encode($notif);
    //     }
    // }

    // function edit($id)
    // {
    //     $query = $this->db->query('SELECT id_surat_keluar, no_surat, perihal, jenis, diusulkan, tujuan, melalui, tgl_kirim, catatan FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
    //     $data['id'] = $query->row();
    //     $this->load->view($this->dir_v.'edit',$data);
    // }

    // function act_edit()
    // {
    //     $this->form_validation->set_rules('no_surat', 'No Surat', 'trim|required|min_length[3]');
    //     $this->form_validation->set_rules('perihal', 'Perihal', 'trim|required|min_length[3]');
    //     $this->form_validation->set_rules('diusulkan', 'Diusulkan oleh', 'trim|required|min_length[3]');
    //     $this->form_validation->set_rules('tujuan', 'Ditujukan ke', 'trim|required|min_length[3]');
    //     $this->form_validation->set_rules('tgl_kirim', 'Tanggal Kirim', 'trim|required');
    //     if($this->form_validation->run() == FALSE){
    //         $notif['notif'] = validation_errors();
    //         $notif['status'] = 1;
    //         echo json_encode($notif);
    //     }else{
    //         $data = array(
    //             'no_surat' => $this->input->post('no_surat'),
    //             'perihal' => $this->input->post('perihal'),
    //             'jenis' => $this->input->post('jenis'),
    //             'tujuan' => $this->input->post('tujuan'),
    //             'diusulkan' => $this->input->post('diusulkan'),
    //             'tgl_kirim' => $this->input->post('tgl_kirim'),
    //             'melalui' => $this->input->post('melalui'),
    //             'catatan' => $this->input->post('catatan')
    //         );
    //         $id_surat = $this->input->post("id_surat");
    //         $this->db->where('id_surat_keluar', $id_surat);
    //         $this->db->update('app_surat_keluar', $data);
    //         $notif['notif'] = 'Perubahan surat '.$this->input->post('no_surat').' berhasil disimpan !';
    //         $notif['status'] = 2;
    //         echo json_encode($notif);
    //     }
    // }

    // function act_del()
    // {
    //     $id = $this->input->post('id_surat');
    //     $query = $this->db->query('SELECT attach1 FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
    //     $row = $query->row_array();

    //     for ($x = 1; $x <= 10; $x++) {
    //         $link = $row["attach$x"];
    //         if(isset($link)){
    //             $old_pic = './upload/keluar/'.$link;
    //             unlink($old_pic);
    //         }
    //     }

    //     $this->db->where('id_surat_keluar', $id);
    //     $this->db->delete('app_surat_keluar');
    //     $notif['notif'] = 'Data surat berhasil di hapus !';
    //     $notif['status'] = 2;
    //     echo json_encode($notif);
    // }

    // function view_status($id)
    // {
    //     $query = $this->db->query('SELECT id_surat_keluar, status FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
    //     $data['id'] = $query->row();
    //     $this->load->view($this->dir_v.'status', $data);
    // }

    // function act_status()
    // {
    //     $data['status'] = $this->input->post("status");
    //     $id_surat = $this->input->post("id_surat");
    //     $this->db->where('id_surat_keluar', $id_surat);
    //     $this->db->update('app_surat_keluar', $data);
    //     $notif['notif'] = 'Perubahan status berhasil !';
    //     $notif['status'] = 2;

        
    //     echo json_encode($notif);
    // }

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

    function send_mail_smtp($token,$email_signer){
        $data=[];
        $this->load->library('email');
        $config = array(
                'protocol'  => 'smtp', 
                'smtp_host' => 'mail.imip.co.id', 
                'smtp_user' => 'patar@imip.co.id', 
                'smtp_pass' => 'zPk2?h51gTkBz&%', 
                'smtp_port' => '587', 
                'mail_type' => 'html', 
                'charset'   => 'iso-8859-1', 
        );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from('patar@imip.co.id','Approval Digital Sign');
        $this->email->to($email_signer); 
        $this->email->subject('Test Mail API');
        // $this->email->message('Test API');  
        // $this->email->message('Approval Link http://localhost/appsign/approve/approve/'.$token.' </b> to cancel http://localhost/appsign/approve/cancel/'. $token .' </b>');
        $mailContent="";
        $mailContent.="For Approval Link Please Click http://localhost/appsign/approve/approvekeluar/".$token."\r\n";
        $mailContent.="or Cancel Link Please Click http://localhost/appsign/approve/cancelkeluar/".$token."\r\n";
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

    function send_mail($id)
    {
        $data['id_surat'] = $id;
        $data['tujuan'] = $this->get_disetujui($id);
        $this->load->view($this->dir_v.'send', $data);
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
        if($this->input->post('disetujui') === '""'){
            $notif['notif'] = 'Penerima surat tidak boleh kosong !';
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $id_surat = $this->input->post('id_surat');
            $query_surat = $this->db->query('SELECT status FROM app_surat_keluar WHERE id_surat_keluar='.$id_surat.' LIMIT 1');
            $data_surat = $query_surat->row();
            $status_surat=$data_surat->status;
            if($status_surat!=2 || $status_surat!=3 || $status_surat!=4){
                if($this->cek_empty_attach($id_surat)){
                    $disetujui = $this->l_surat_keluar->FilterArray($this->input->post('disetujui'));
                    $id_disetujui = (int)str_replace('"', '', $disetujui);
                    $query = $this->db->query('SELECT id, email_user FROM t_signer WHERE id='. $id_disetujui .' LIMIT 1');
                    $data = $query->row();
                    $email_signer=$data->email_user;
                    $token=$this->generate_token();
                    $update['signer'] = $data->id;
                    $update['disetujui'] = $disetujui;
                    $update['status'] = 3;
                    $update['approval_status'] = 1;
                    $update['token'] = $token;
                    //token_time
                    $token_time = date_create()->format('Y-m-d H:i:s');
                    $update['token_time'] = $token_time;
                    $send_mail_smtp=$this->send_mail_smtp($token,$email_signer);

                    if($send_mail_smtp){
                        $this->db->where('id_surat_keluar', $id_surat);
                        $this->db->update('app_surat_keluar', $update);
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
            }else{
                $notif['notif'] = 'Surat tidak bisa dikirim lagi. !';
                $notif['status'] = 1;
                echo json_encode($notif);
            }
            
        }
    }

    function sign_document($id)
    {
        $data['id_surat'] = $id;
        $this->load->view($this->dir_v.'sign', $data);
    }

    function act_sign_document()
    {
        if($this->input->post('disetujui') === '""'){
            $notif['notif'] = 'Penanda tangan tidak boleh kosong !';
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            $id_surat = $this->input->post('id_surat');
            $query = $this->db->query('SELECT id_surat_keluar, document_id FROM app_surat_keluar WHERE id_surat_keluar='.$id_surat.' LIMIT 1');
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
            if($response_text->JSONFile->result!='00'){
                $notif['notif'] = 'Error : '. $response_text->JSONFile->notif;
                $notif['status'] = 1;
                echo json_encode($notif);
            }else{
                $update['link_document'] = $response_text->JSONFile->link;
                $this->db->where('id_surat_keluar', $id_surat);
                $this->db->update('app_surat_keluar', $update);
                $notif['notif'] = 'Document Signing Succeed';
                $notif['status'] = 2;
                $notif['id'] = $id_surat;
                $notif['url_api']=$response_text->JSONFile->link;
                echo json_encode($notif); 
            }

        }
    }


    function cek_empty_attach($id)
    {
        $get_attach = $this->db->query('SELECT attach1 FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $rows = $get_attach->row();
        if(!empty($rows->attach1)){
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

    // function posisi_sign($id)
    // {
    //     $data['id_surat'] = $id;
    //     $this->load->view($this->dir_v.'posisi', $data);
    // }

    // function sign_pdf($id)
    // {
    //     $query = $this->db->query('SELECT * FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
    //     $rows = $query->row();
    //     $data['id']=$id;
    //     $data['css'] = array(
    //         'src/css/style.css');
    //     $data['js'] = array(
    //         'lib/jquery/jquery-3.3.1.min.js',
    //         'lib/bootstrap-4.1.3/dist/js/bootstrap.min.js',
    //         'lib/pdfjs/pdf.js',
    //         'lib/interact/interact.min.js',
    //         'src/js/admin/app.js',
    //         'src/js/admin/pdf.config.js',
    //         'src/js/admin/signature.config.js');
    //     $data['panel'] = '<i class="fa fa-inbox"></i> &nbsp;<b>PDF</b>';
    //     $this->l_skin->config($this->dir_v.'pdf_signing', $data);
    // }

    // function act_sign_doc(){
    //     $id_surat = $this->input->post("id");
    //     $llx = $this->input->post("llx");
    //     $urx = $this->input->post("urx");
    //     $lly = $this->input->post("lly");
    //     $ury = $this->input->post("ury");
    //     $this->form_validation->set_rules('llx', 'llx Failed', 'trim|required|greater_than[0]');
    //     $this->form_validation->set_rules('lly', 'lly Failed', 'trim|required|greater_than[0]');
    //     $this->form_validation->set_rules('urx', 'urx Failed', 'trim|required|greater_than[0]');
    //     $this->form_validation->set_rules('ury', 'ury Failed', 'trim|required|greater_than[0]');
    //     if($this->form_validation->run() == FALSE){
    //         $notif['notif'] = validation_errors();
    //         $notif['status'] = 1;
    //         echo json_encode($notif);
    //     }else{
    //          $data['status'] = 2;
    //         $data['llx'] = $llx;
    //         $data['lly'] = $lly;
    //         $data['urx'] = $urx;
    //         $data['ury'] = $ury;
    //         $this->db->where('id_surat_keluar', $id_surat);
    //         $this->db->update('app_surat_keluar', $data);
    //         $notif['notif'] = 'Set Posisi Berhasil !';
    //         $notif['status'] = 2;
    //         echo json_encode($notif);
    //     }  
    // }

    function upload_vendor($id)
    {
        $data['id_surat'] = $id;
        $this->load->view($this->dir_v.'vendor', $data);
    }

    function upload_vendor_act_btn(){
        $id_surat = $this->input->post("id_surat");
        $get_attach = $this->db->query('SELECT path_folder, llx, lly, urx, ury, attach1 FROM app_surat_keluar WHERE id_surat_keluar='.$id_surat.' LIMIT 1');
        $rows = $get_attach->row();
        $doc_id = rand(0,9999).time();
        $real_t_file = $rows->path_folder;
        $llx = $rows->llx;
        $lly = $rows->lly;
        $urx = $rows->urx;
        $ury = $rows->ury;
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
                $this->db->where('id_surat_keluar', $id_surat);
                $this->db->update('app_surat_keluar', $update);
                $notif['notif'] = 'Upload file ke server Digisign Berhasil!';
                $notif['status'] = 2;
            } 
        }else{
            $notif['notif'] = 'Api Connection Error !';
            $notif['status'] = 1;
        }

        echo json_encode($notif);
    }
}