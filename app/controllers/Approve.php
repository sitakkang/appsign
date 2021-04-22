<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approve extends CI_Controller {
    public $dir_l = 'admin/';
    public function __construct(){
        parent::__construct();
        $this->load->library($this->dir_l.'l_surat_keluar');
    }

    public function index()
	{
		echo 'Hello Index';
	}

	public function approvekeluar($token)
	{
        $approval='Approval Denied </br>';
		$get_surat = $this->db->query("SELECT id_surat_keluar, status, path_folder, llx, lly, urx, ury, attach1, signer, token_time, token_time_exp, token FROM app_surat_keluar WHERE token='".$token."' LIMIT 1");
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
                if($minutes>=3){
                    $update['status'] = 6;
                    $this->db->where('id_surat_keluar', $rows->id_surat_keluar);
                    $this->db->update('app_surat_keluar', $update);
                    $approval='Token Expired';
                    $approval.='Please Request Token Again.';
                    $approval.='Click ';

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
                    $get_signer = $this->db->query("SELECT id, kuser_production, kuser_sandbox, email_user, email_digisign, name FROM t_signer WHERE id='".$rows->signer."' LIMIT 1");
                    $rows_signer = $get_signer->row();
                    if($type==1){
                        $kuser=$rows_signer->kuser_sandbox;
                    }else{
                        $kuser=$rows_signer->kuser_production;
                    }
                    $email_user=$rows_signer->email_user;
                    $email_dgsign=$rows_signer->email_digisign;
                    $name_signer=$rows_signer->name;
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
                                        "name"=> $name_signer, 
                                        "email"=> $email_user
                                    ]),
                                    "req-sign" => array([
                                        "name" =>"Approval Document Sign", 
                                        "email"=>$email_dgsign, 
                                        "aksi_ttd"=>"at", 
                                        "kuser"=>$kuser,
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
                    //send Document
                    $headers=array('Authorization: Bearer gLgyVTNNZrEJPIiu1VUCOMpR16xdGa9aeuk5cVeN44vQOpi8VTkAZQwiqtz3EM');
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
                                            "userid"=> "adminimip@tandatanganku.com", 
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
                                    $update['file_downloaded'] = $linkAttach;
                                    $update['status'] = 5;
                                    $update['document_id'] = $doc_id;
                                    $this->db->where('id_surat_keluar', $rows->id_surat_keluar);
                                    $this->db->update('app_surat_keluar', $update);
                                    $approval='Approved';
                                }
                            }
                        }else{
                            $approval='Not Approved';
                        } 
                    }else{
                        $approval='Api Connection Error !';
                    }
                    
                }
            }elseif($status_all==6){
                $approval.='Token Expired.</br>';
                $approval.='Please Request Token Again.</br>';
                $approval.='Click </br>';
            }else{
                $approval.='This Document has been signed before.</br>';
            }  
        }
        echo $approval;
	}

	public function cancelkeluar($token)
	{
		$update['status'] = 4;
        $this->db->where('token', $token);
        $this->db->update('app_surat_masuk', $update);
        $approval='Cancel Approve';
		echo $approval;
	}

}