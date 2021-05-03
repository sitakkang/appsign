<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_keluar extends CI_Controller {

    public $dir_v = 'admin/surat_keluar/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_superadmin();
        $this->load->library(array('phpmailer_lib'));
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
            'src/js/admin/surat_keluar.js');
        $data['panel'] = '<i class="fa fa-envelope-open-text"></i> &nbsp;<b>Surat Keluar</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }


    public function setTokenExpired($id){
        $get_surat = $this->db->query("SELECT id_surat_keluar, status, path_folder, llx, lly, urx, ury, attach1, signer, token_time, token_time_exp FROM app_surat_keluar WHERE id_surat_keluar='".$id."' LIMIT 1");
        $rows = $get_surat->row();
        $date_ = gmdate('Y-m-d H:i:s', time()+60*60*7);
        $now=new DateTime($date_);
        $then = new DateTime($rows->token_time);
        $diff = $now->diff($then);
        $minutes = ($diff->format('%a') * 1440) + // total days converted to minutes
                   ($diff->format('%h') * 60) +  
                    $diff->format('%i');      
        if($minutes>=3){
            $update['status'] = 6;
            $this->db->where('id_surat_keluar', $id);
            $this->db->update('app_surat_keluar', $update);
        }
    }

    public function table()
    {
        $get_all = $this->db->query('SELECT * FROM app_surat_keluar ORDER BY id_surat_keluar DESC');

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $data = array();
        $i = 1;
        foreach($get_all->result() as $id) {
            $action = '<a href="" class="view_act_btn" data-id="'.$id->id_surat_keluar.'" title="View Detail" style="color:#0F9647;"><button><i class="fa fa-search"></i></button></a>';
            if($id->status==3){
                $this->setTokenExpired($id->id_surat_keluar);
            }
            $data[] = array(
                "DT_RowId" => $id->id_surat_keluar,
                '0' => $i++,
                '1' => $id->no_surat,
                '2' => $this->m_surat_keluar->user_surat_keluar($id->user),
                '3' => $id->tgl_kirim,
                '4' => $id->diusulkan,
                '5' => $id->jenis,
                '6' => $id->melalui,
                '7' => $this->m_surat_keluar->label_status_keluar($id->status, $id->id_surat_keluar),
                '8' => $action.' '.$this->m_surat_keluar->attachment(array($id->attach1)).' '.$this->m_surat_keluar->keluar_act_btn($id->id_surat_keluar, $id->no_surat).' '.$this->m_surat_keluar->attachment_downloaded(array($id->file_downloaded)),
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

    function detail($id)
    {
        $query = $this->db->query('SELECT * FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $data['id'] = $query->row();
        $this->load->view($this->dir_v.'detail',$data);
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

    function sendEmail($token,$params_user,$path_folder){
        
        $mail    = $this->phpmailer_lib->load(); 
        // $mail = new PHPMailer();
        $mail->IsSMTP();
        // $mail->SMTPDebug = 3;
        $mail->SMTPAuth = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port     = 587;  
        $mail->Username = "patar.sitakkang@gmail.com";
        $mail->Password = "Pathuli90CR7ABCD";
        $mail->Host     = "tls://smtp.gmail.com";
        $mail->Mailer   = "smtp";

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->SetFrom("patar.sitakkang@gmail.com", "Tanda Tangan Digital IMIP");
        $mail->AddReplyTo("patar.sitakkang@gmail.com", "PT. IMIP");
        $mail->AddAddress($params_user['email_signer']);
        $mail->Subject = "Approval Digital Signature";
        $mail->WordWrap   = 80;
        $data['token'] = $token;
        $data['name_signer']=$params_user['name'];

        $message = $this->load->view($this->dir_v . 'v_send', $data, TRUE);
        $mail->MsgHTML($message);
        $mail->AddAttachment($path_folder, $name = 'dokumen kantor',  $encoding = 'base64', $type = 'application/pdf');
        $mail->IsHTML(true);
        if($mail->Send()){ 
            return true;
        }else{ 
            return false;
        }
    }

    function sendEmailIMIP($token,$email_signer,$path_folder){
        $mail    = $this->phpmailer_lib->load(); 
		$mail->isSMTP();
        $mail->Host       = 'mail.imip.co.id';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'patar@imip.co.id';
        $mail->Password   = 'zPk2?h51gTkBz&%';
        $mail->SMTPSecure = 'tsl';
        $mail->Port       = 587;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->setFrom('patar@imip.co.id', 'Tanda Tangal Digital PT. IMIP');
        $mail->addReplyTo('patar@imip.co.id', 'Tanda Tangal Digital PT. IMIP');
        $mail->addAddress($email_signer);
        $mail->Subject = 'Approval Document';
        
        //kirim dokumen
        // $mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].'/markdown/upload/imip.pdf', $name = 'dokumen kantor',  $encoding = 'base64', $type = 'application/pdf');
        $mail->AddAttachment($path_folder, $name = 'dokumen kantor',  $encoding = 'base64', $type = 'application/pdf');
        
        $mail->isHTML(true);

        $data['token'] = $token;

        $message = $this->load->view($this->dir_v . 'v_send', $data, TRUE);
		$mail->Body = $message;
        
        if($mail->send()){
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
        for($i=0;$i<15; $i++){
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
            $query_surat = $this->db->query('SELECT status, path_folder FROM app_surat_keluar WHERE id_surat_keluar='.$id_surat.' LIMIT 1');
            $data_surat = $query_surat->row();
            $status_surat=$data_surat->status;
            // if($status_surat!=2 || $status_surat!=3 || $status_surat!=4){
            if($status_surat!=5 || $status_surat!=0 || $status_surat!=1){
                if($this->cek_empty_attach($id_surat)){
                    $disetujui = $this->l_surat_keluar->FilterArray($this->input->post('disetujui'));
                    $id_disetujui = (int)str_replace('"', '', $disetujui);
                    $query = $this->db->query('SELECT id, email_user,name FROM t_signer WHERE id='. $id_disetujui .' LIMIT 1');
                    $data = $query->row();
                    $email_signer=$data->email_user;
                    $name_signer=$data->name;
                    $token=$this->generate_token();
                    $update['signer'] = $data->id;
                    $update['disetujui'] = $disetujui;
                    $update['status'] = 3;
                    $update['approval_status'] = 1;
                    $update['token'] = $token;
                    //token_time
                    $token_time = $this->l_surat_keluar->DateTimeNow();
                    $exp_token_time=date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($token_time)));
                    $update['token_time'] = $token_time;
                    $update['token_time_exp'] = $exp_token_time;
                    $path_folder=$data_surat->path_folder;
                    $params_user=[];
                    $params_user=['email_signer'=>$email_signer, 'name'=>$name_signer];
                    $send_mail_smtp = $this->sendEmail($token,$params_user,$path_folder);
                    if($send_mail_smtp==true){
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

    // function act_sign_document()
    // {
    //     if($this->input->post('disetujui') === '""'){
    //         $notif['notif'] = 'Penanda tangan tidak boleh kosong !';
    //         $notif['status'] = 1;
    //         echo json_encode($notif);
    //     }else{
    //         $id_surat = $this->input->post('id_surat');
    //         $query = $this->db->query('SELECT id_surat_keluar, document_id FROM app_surat_keluar WHERE id_surat_keluar='.$id_surat.' LIMIT 1');
    //         $data = $query->row();
    //         $doc_id=$data->document_id;
    //         $options = [
    //             'jsonfield' => json_encode(
    //                 [
    //                     'JSONFile' => [
    //                         "userid"=> "adminimip@tandatanganku.com", 
    //                         "document_id" => $doc_id, 
    //                         "email_user" =>"adminimip@tandatanganku.com", 
    //                         "view_only" => false
    //                     ],
    //                 ],
    //             )
    //         ];
    //         $headers=array('Authorization: Bearer gLgyVTNNZrEJPIiu1VUCOMpR16xdGa9aeuk5cVeN44vQOpi8VTkAZQwiqtz3EM');
    //         $curl = curl_init();
    //         curl_setopt($curl, CURLOPT_URL, "https://api.tandatanganku.com/gen/genSignPage.html");
    //         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    //         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    //         curl_setopt($curl, CURLOPT_VERBOSE, 1);
    //         curl_setopt($curl, CURLOPT_POST, true);
    //         curl_setopt($curl, CURLOPT_POSTFIELDS, $options);
    //         curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
    //         $response = curl_exec($curl);
    //         $response_text = json_decode($response);
    //         if($response_text->JSONFile->result!='00'){
    //             $notif['notif'] = 'Error : '. $response_text->JSONFile->notif;
    //             $notif['status'] = 1;
    //             echo json_encode($notif);
    //         }else{
    //             $update['link_document'] = $response_text->JSONFile->link;
    //             $this->db->where('id_surat_keluar', $id_surat);
    //             $this->db->update('app_surat_keluar', $update);
    //             $notif['notif'] = 'Document Signing Succeed';
    //             $notif['status'] = 2;
    //             $notif['id'] = $id_surat;
    //             $notif['url_api']=$response_text->JSONFile->link;
    //             echo json_encode($notif); 
    //         }

    //     }
    // }


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
        $query = $this->db->query('SELECT signer FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $rows = $query->row();
        if(empty($rows->signer)){
            return NULL;
        }else{
            return $rows->signer;
        }
    }

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