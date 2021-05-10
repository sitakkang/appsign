<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approve extends CI_Controller {
    public $dir_l = 'admin/';
    public $dir_v = 'approve/';
    public function __construct(){
        parent::__construct();
        $this->load->library($this->dir_l.'l_surat_keluar');
        $this->load->library(array('phpmailer_lib'));
    }

    public function index()
    {
        echo 'Hello Index';
    }

    function sendEmail($jenis_action,$params_user,$path_folder){
        
        $mail    = $this->phpmailer_lib->load(); 
        $mail->IsSMTP();
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
        if($jenis_action=='approve'){
            $mail->Subject = "Approval Digital Signature";
        }else{
            $mail->Subject = "Rejected Digital Signature";
        }
        $mail->WordWrap   = 80;
        $data['name_signer']=$params_user['user_name'];
        if($jenis_action=='approve'){
            $data['message']='Approval Digital Signature berhasil pada '.$params_user['action_time'].'.';
            $mail->AddAttachment($path_folder, $name = 'dokumen kantor',  $encoding = 'base64', $type = 'application/pdf');
        }else{
            $data['message']='Rejected Digital Signature berhasil dilakukan pada '.$params_user['action_time'].'.';
        }
        $message = $this->load->view($this->dir_v . 'v_approve_signature', $data, TRUE);
        $mail->MsgHTML($message);
        $mail->IsHTML(true);
        if($mail->Send()){ 
            return true;
        }else{ 
            return false;
        }
    }

    public function updateAction($params){
        $update['action_time'] = $params['action_time'];
        $update['last_action'] = $params['last_action'];
        $this->db->where('id_surat_keluar', $params['id_surat_keluar']);
        $this->db->update('app_surat_keluar', $update);
    }
    public function approvekeluar($token)
    {   
        $approval='Approval Denied </br>';
        $get_surat = $this->db->query("SELECT id_surat_keluar, status, path_folder, llx, lly, urx, ury, attach1, signer, token_time, token_time_exp, token, page,last_action, action_time, document_name FROM app_surat_keluar WHERE token='".$token."' LIMIT 1");
        $rows = $get_surat->row();
        if(empty($rows->token)){
            $approval='Token is not Exist.';
        }else{
            $status_all=$rows->status;
            if($status_all==3){
                $date_ = gmdate('Y-m-d H:i:s', time()+60*60*7);
                $now=new DateTime($date_);
                $then = new DateTime($rows->token_time);
                $diff = $now->diff($then);
                $minutes = ($diff->format('%a') * 1440) + // total days converted to minutes
                           ($diff->format('%h') * 60) +  
                            $diff->format('%i');          
                if($minutes>=30){
                    $update['status'] = 6;
                    $this->db->where('id_surat_keluar', $rows->id_surat_keluar);
                    $this->db->update('app_surat_keluar', $update);
                    $query_signer = $this->db->query('SELECT * FROM t_signer WHERE id='. $rows->signer .' LIMIT 1');
                    $data_signer = $query_signer->row();
                    $name_signer=$data_signer->name;
                    $data=['id_surat'=>$rows->id_surat_keluar,'name_signer'=>$name_signer];
                    $approval=$this->load->view($this->dir_v . 'v_infoexpired', $data, TRUE);

                }else{
                    $get_apisetup = $this->db->query("SELECT * FROM api_activation WHERE status='active' LIMIT 1");
                    $rows_apisetup = $get_apisetup->row();
                    $type=$rows_apisetup->id;
                    $url_send_document='';
                    $url_download_document='';
                    $get_apivoid = $this->db->query("SELECT * FROM tbl_api_action WHERE type='".$type."'");
                    foreach($get_apivoid->result() as $id) {
                        if($id->api_key=='send_document'){
                            $url_send_document=$id->url;
                        }elseif($id->api_key=='download_document'){
                            $url_download_document=$id->url;
                        }
                    }
                    $get_signer = $this->db->query("SELECT * FROM t_signer WHERE id='".$rows->signer."' LIMIT 1");
                    $rows_signer = $get_signer->row();
                    $token_api_all="";
                    $user_id_digisign="";
                    if($type==1){
                        $kuser=$rows_signer->kuser_sandbox;
                        $user_id_digisign=$rows_signer->digisign_user_id_sandbox;
                        $token_api_all=$rows_signer->token_sandbox;
                    }else{
                        $kuser=$rows_signer->kuser_production;
                        $user_id_digisign=$rows_signer->digisign_user_id_production;
                        $token_api_all=$rows_signer->token_production;
                    }
                    $email_user=$rows_signer->email_user;
                    $email_dgsign=$rows_signer->email_digisign;
                    $name_signer=$rows_signer->name;
                    $doc_id = $rows->document_name;
                    $real_t_file = $rows->path_folder;
                    $llx = $rows->llx;
                    $lly = $rows->lly;
                    $urx = $rows->urx;
                    $ury = $rows->ury;
                    $page = $rows->page;
                    $options_at = [
                        'jsonfield' => json_encode(
                            [
                                'JSONFile' => [
                                    "userid"=> $user_id_digisign, 
                                    "document_id" => $doc_id, 
                                    "payment" => "3", 
                                    "redirect" => true, 
                                    "sequence_option"=>false,
                                    "send-to" => array([
                                        "name"=> $name_signer, 
                                        "email"=> $email_user
                                    ]),
                                    "req-sign" => array([
                                        "name" =>"Approval Document Sign", 
                                        "email"=>$email_dgsign, 
                                        "aksi_ttd"=>"at", 
                                        "kuser"=>$kuser,
                                        "user"=>"ttd1", 
                                        "page"=>$page, 
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
                    //send Document
                    $headers=array('Authorization: Bearer '.$token_api_all);
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $url_send_document);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($curl, CURLOPT_VERBOSE, 1);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $options_at);
                    curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
                    $response = curl_exec($curl);
                    $response_text = json_decode($response);
                    if(isset($response_text->JSONFile->result)){
                       if($response_text->JSONFile->result=='00'){
                            //download Document by API
                            $download_val = [
                                'jsonfield' => json_encode(
                                    [
                                        'JSONFile' => [
                                            "userid"=> $email_dgsign, 
                                            "document_id" => $doc_id, 
                                        ],
                                    ],
                                )
                            ];
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $url_download_document);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($curl, CURLOPT_VERBOSE, 1);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $download_val);
                            curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
                            $response_download = curl_exec($curl);
                            $response_text_download = json_decode($response_download);
                            if(isset($response_text_download->JSONFile->result)){
                                if($response_text_download->JSONFile->result=='00'){
                                    $fileName=$doc_id.'.pdf';
                                    $tahun = $this->l_surat_keluar->DateYear();
                                    $bulan = $this->l_surat_keluar->DateMonth();
                                    $targetPath = './upload/keluar/'.$tahun.'/'.$bulan.'/';
                                    $targetFile = $targetPath.$fileName;
                                    if(!file_exists($targetPath)){mkdir($targetPath, 0777, true);}
                                    $linkAttach = $tahun.'/'.$bulan.'/'.$fileName;
                                    file_put_contents($targetFile, base64_decode($response_text_download->JSONFile->file));
                                    $t_file='upload/keluar/'.$tahun.'/'.$bulan.'/'.$fileName;
                                    $real_t_file=realpath(APPPATH . '../' . $t_file);
                                    $update['path_folder_downloaded'] = $linkAttach;
                                    $update['file_downloaded'] = $linkAttach;
                                    $update['status'] = 5;
                                    $update['document_id'] = $doc_id;
                                    $this->db->where('id_surat_keluar', $rows->id_surat_keluar);
                                    $this->db->update('app_surat_keluar', $update);
                                    $data=['name_signer'=>$name_signer];
                                    $approval=$this->load->view($this->dir_v . 'v_infoapprove', $data, TRUE);
                                    $jenis_action='approve';
                                    $params=[];
                                    $action_time=$this->l_surat_keluar->DateTimeNow();
                                    $last_action='approve';
                                    $params=['last_action'=>$last_action,'action_time'=>$action_time,'id_surat_keluar'=>$rows->id_surat_keluar];
                                    $this->updateAction($params);
                                    $params_user=[];
                                    $params_user=['email_signer'=>$email_user,'user_name'=>$name_signer,'action_time'=>$action_time];
                                    $sendEmail=$this->sendEmail($jenis_action,$params_user,$real_t_file);

                                }else{
                                    $update['status'] = 7;
                                    $update['document_id'] = $doc_id;
                                    $this->db->where('id_surat_keluar', $rows->id_surat_keluar);
                                    $this->db->update('app_surat_keluar', $update);
                                    $message='Approval Digital Signature berhasil tapi donwload file gagal. Info : '.$response_text_download->JSONFile->notif;
                                    $data=['name_signer'=>$name_signer,'message'=>$message];
                                    $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
                                }
                            }else{
                                $update['status'] = 7;
                                $update['document_id'] = $doc_id;
                                $this->db->where('id_surat_keluar', $rows->id_surat_keluar);
                                $this->db->update('app_surat_keluar', $update);
                                $message='Approval Digital Signature berhasil tapi donwload file gagal. Info : Bermasalah pada koneksi saat download file.';
                                $data=['name_signer'=>$name_signer,'message'=>$message];
                                $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
                            }
                        }else{
                            $message='Approval Digital Signature gagal. Info : '.$response_text->JSONFile->notif;
                            $data=['name_signer'=>$name_signer,'message'=>$message];
                            $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
                        } 
                    }else{

                        $message='Approval Digital Signature gagal. Bermasalah pada koneksi internet anda. Silahkan hubungi admin untuk informasi lenih lanjut.';
                        $data=['name_signer'=>$name_signer,'message'=>$message];
                        $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
                    }
                    
                }
            }elseif($status_all==6){
                $query_signer = $this->db->query('SELECT name FROM t_signer WHERE id='. $rows->signer .' LIMIT 1');
                $data_signer = $query_signer->row();
                $name_signer=$data_signer->name;
                $data=['id_surat'=>$rows->id_surat_keluar,'name_signer'=>$name_signer];
                $approval=$this->load->view($this->dir_v . 'v_infoexpired', $data, TRUE);
            }elseif($status_all==4){
                $query_signer = $this->db->query('SELECT name FROM t_signer WHERE id='. $rows->signer .' LIMIT 1');
                $data_signer = $query_signer->row();
                $name_signer=$data_signer->name;
                $message='Approval Digital Signature gagal. Document ini telah rejected sebelumnya pada '.$rows->action_time;
                $data=['name_signer'=>$name_signer,'message'=>$message];
                $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);

            }else{
                $query_signer = $this->db->query('SELECT name FROM t_signer WHERE id='. $rows->signer .' LIMIT 1');
                $data_signer = $query_signer->row();
                $name_signer=$data_signer->name;
                $message='Approval Digital Signature gagal. Document mengalami perubahan status.';
                $data=['name_signer'=>$name_signer,'message'=>$message];
                $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
            }  
        }
        
        echo $approval;
    }

    public function cancelkeluar($token)
    {
        $get_surat = $this->db->query("SELECT * FROM app_surat_keluar WHERE token='".$token."' LIMIT 1");
        $rows = $get_surat->row();
        if(!empty($rows->signer)){
            $query_signer = $this->db->query('SELECT name,email_user FROM t_signer WHERE id='. $rows->signer .' LIMIT 1');
            $data_signer = $query_signer->row();
            $name_signer=$data_signer->name;
            $status=$rows->status;
            if($status!=5){
                if($status==4){
                    $message='Digital signature rejected sudah dilakukan sebelumnya pada '.$rows->action_time;
                    $data=['id_surat'=>$rows->id_surat_keluar,'name_signer'=>$name_signer,'message'=>$message];
                    $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
                }elseif($status==3){
                    $jenis_action='reject';
                    $params=[];
                    $action_time=$this->l_surat_keluar->DateTimeNow();
                    $params=['last_action'=>$jenis_action,'action_time'=>$action_time,'id_surat_keluar'=>$rows->id_surat_keluar];
                    $this->updateAction($params);
                    $update['status'] = 4;
                    $this->db->where('token', $token);
                    $this->db->update('app_surat_keluar', $update);
                    $real_t_file='';
                    $email_user=$data_signer->email_user;
                    $params_user=['email_signer'=>$email_user,'user_name'=>$name_signer,'action_time'=>$action_time];
                    $sendEmail=$this->sendEmail($jenis_action,$params_user,$real_t_file);
                    $message='Your Digital signature has been Rejected.';
                    $data=['id_surat'=>$rows->id_surat_keluar,'name_signer'=>$name_signer,'message'=>$message];
                    $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);  
                }elseif($status==6){
                    $data=['id_surat'=>$rows->id_surat_keluar,'name_signer'=>$name_signer];
                    $approval=$this->load->view($this->dir_v . 'v_infoexpired', $data, TRUE);
                }else{
                    $message='Digital Signature rejected gagal. Document mengalami perubahan status';
                    $data=['name_signer'=>$name_signer,'message'=>$message];
                    $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE); 
                }
                
            }else{
                $message='Digital Signature rejected gagal. Document ini sudah approved sebelumnya pada '.$rows->action_time;
                $data=['name_signer'=>$name_signer,'message'=>$message];
                $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
            }    
        }else{
            $name_signer='User';
            $message='Digital Signature rejected gagal. Token tidak ditemukan';
            $data=['name_signer'=>$name_signer,'message'=>$message];
            $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
        }
        echo $approval; 
        
    }

    public function request_token($id_surat)
    {
        $approval='';
        $get_surat = $this->db->query("SELECT * FROM app_surat_keluar WHERE id_surat_keluar='".$id_surat."' LIMIT 1");
        $rows = $get_surat->row();
        $query_signer = $this->db->query('SELECT name FROM t_signer WHERE id='. $rows->signer .' LIMIT 1');
        $data_signer = $query_signer->row();
        $name_signer=$data_signer->name;
        if($rows->status==6){
            $data=[];
            $update['status'] = 2;
            $this->db->where('id_surat_keluar', $id_surat);
            $this->db->update('app_surat_keluar', $update);
            $data=['id_surat'=>$rows->id_surat_keluar,'name_signer'=>$name_signer];
            $approval=$this->load->view($this->dir_v . 'v_inforeqtoken', $data, TRUE); 
        }elseif($rows->status==5){
            $message='Request Token Digital Signature gagal. Document telah ditandatangani sebelumnya pada '.$rows->action_time;
            $data=['name_signer'=>$name_signer,'message'=>$message];
            $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
        }else{
            $message='Request Token Digital Signature gagal. Status document tidak memenuhi.';
            $data=['name_signer'=>$name_signer,'message'=>$message];
            $approval=$this->load->view($this->dir_v . 'v_infocancel', $data, TRUE);
        }
        
        echo $approval;
    }

}