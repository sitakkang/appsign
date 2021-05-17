<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surat_keluar_admin extends CI_Controller {

    public $dir_v = 'surat_keluar/surat_keluar_admin/';
    public $dir_m = 'surat_keluar/';
    public $dir_l = 'surat_keluar/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_akses();
        // $this->m_auth->check_superadmin();
        $this->load->library(array('phpmailer_lib'));
        $this->load->model($this->dir_m.'m_surat_keluar_admin');
        $this->load->library($this->dir_l.'l_surat_keluar_admin');
    }

    function view_status($id)
    {
        $query = $this->db->query('SELECT * FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $data['id'] = $query->row();
        $this->load->view($this->dir_v.'status',$data);
    }

    function act_status()
    {
        // $query = $this->db->query('SELECT * FROM app_surat_keluar WHERE id_surat_keluar='.$this->input->post("id_surat").' LIMIT 1');
        // $rows = $query->row();
        // if($this->input->post("status")==5){
        //     if(empty($rows->attach1)){
                // $notif['notif'] = 'Perubahan status gagal! File Attachment tidak bisa kosong.';
                // $notif['status'] = 1;
            // }else{
                $data['status'] = $this->input->post("status");
                $id_surat = $this->input->post("id_surat");
                $this->db->where('id_surat_keluar', $id_surat);
                $this->db->update('app_surat_keluar', $data);
                $notif['notif'] = 'Perubahan status berhasil !';
                $notif['status'] = 2;
        //     }
        // }else{
        //     $data['status'] = $this->input->post("status");
        //     $id_surat = $this->input->post("id_surat");
        //     $this->db->where('id_surat_keluar', $id_surat);
        //     $this->db->update('app_surat_keluar', $data);
        //     $notif['notif'] = 'Perubahan status berhasil !';
        //     $notif['status'] = 2;
        // }
        
        echo json_encode($notif);
    }

    function sign_pdf($id)
    {
        $query = $this->db->query('SELECT * FROM app_surat_keluar WHERE id_surat_keluar='.$id.' AND jenis_ttd="Digital" LIMIT 1');
        $rows = $query->row();
        $path_folder=$rows->path_folder;
        $b64Doc = chunk_split(base64_encode(file_get_contents($path_folder)));
        $data['id']=$id;
        $data['pdf_base64']=$b64Doc;
        $data['attachment']=site_url('upload/keluar/'.$rows->attach1);
        $data['css'] = array(
            'src/css/style.css');
        $data['js'] = array(
            'lib/jquery/jquery-3.3.1.min.js',
            'lib/bootstrap-4.1.3/dist/js/bootstrap.min.js',
            'lib/interact/interact.min.js',
            'src/js/surat_keluar/app_admin.js',
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.2.228/pdf.min.js',
            'https://unpkg.com/ionicons@4.4.2/dist/ionicons.js',
            'src/js/surat_keluar_admin/signature.config.js');
        $data['path_folder']=$path_folder;
        $data['panel'] = '<i class="fa fa-inbox"></i> &nbsp;<b>PDF</b>';
        $this->l_skin->config($this->dir_v.'pdf_signing', $data);
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
            'src/js/surat_keluar/surat_keluar_admin.js');
        $data['panel'] = '<i class="fa fa-envelope-open-text"></i> &nbsp;<b>Surat Keluar</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    function act_sign_doc(){
        $id_surat = $this->input->post("id");
        $query = $this->db->query('SELECT id_surat_keluar, status, no_surat, perihal, jenis, diusulkan, tujuan, melalui, tgl_kirim, catatan FROM app_surat_keluar WHERE id_surat_keluar='.$id_surat.' LIMIT 1');
        $rows = $query->row();
        $no_surat=$rows->no_surat;
        $status=$rows->status;
        if($status==1 || $status==2 || $status==4 || $status==6){
            $llx = $this->input->post("llx");
            $urx = $this->input->post("urx");
            $lly = $this->input->post("lly");
            $ury = $this->input->post("ury");
            $page = $this->input->post("pageNow");
            $this->form_validation->set_rules('llx', 'llx Failed', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('lly', 'lly Failed', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('urx', 'urx Failed', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('ury', 'ury Failed', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('pageNow', 'Page Failed', 'trim|required|greater_than[0]');
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
                $data['page'] = $page;
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

    public function setTokenExpired($id){
        $get_surat = $this->db->query("SELECT id_surat_keluar, status, path_folder, llx, lly, urx, ury, attach1, signer, token_time, token_time_exp FROM app_surat_keluar WHERE id_surat_keluar='".$id."' and jenis_ttd='Digital' LIMIT 1");
        $rows = $get_surat->row();
        if(!empty($rows->id_surat_keluar)){
           $date_ = gmdate('Y-m-d H:i:s', time()+60*60*7);
            $now=new DateTime($date_);
            $then = new DateTime($rows->token_time);
            $diff = $now->diff($then);
            $minutes = ($diff->format('%a') * 1440) + // total days converted to minutes
                       ($diff->format('%h') * 60) +  
                        $diff->format('%i');      
            if($minutes>=30){
                $update['status'] = 6;
                $this->db->where('id_surat_keluar', $id);
                $this->db->update('app_surat_keluar', $update);
            } 
        }
        
    }

    function add()
    {
        $this->load->view($this->dir_v.'add');
    }

    function act_add()
    {
        $jenis_ttd=$this->input->post('jenis_ttd');
        $user_id = $this->session->userdata('sess_id');
        $this->form_validation->set_rules('no_surat', 'No Surat', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tujuan', 'Ditujukan', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('diusulkan', 'Diusulkan', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tgl_kirim', 'Tanggal Kirim', 'trim|required');
        $this->form_validation->set_rules('melalui', 'Type', 'trim|required');
        $this->form_validation->set_rules('asal_surat', 'Asal Surat', 'trim|required');
        if($jenis_ttd=="Digital"){
            $this->form_validation->set_rules('signer', 'Penanda Tangan', 'trim|required');
            $signer=$this->input->post('signer');
        }else{
            $signer=NULL;
        }
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
                'asal_surat' => $this->input->post('asal_surat'),
                'signer' => $signer,
                'jenis_ttd'=> $this->input->post('jenis_ttd'),
                'flag' => 0
            );
            $this->db->insert('app_surat_keluar', $data);
            $notif['notif'] = 'Data surat '.$this->input->post('no_surat').' berhasil disimpan !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function act_edit()
    {
        $jenis_ttd=$this->input->post('jenis_ttd');
        if($jenis_ttd=="Digital"){
            $this->form_validation->set_rules('signer', 'Penanda Tangan', 'trim|required');
        }
        $this->form_validation->set_rules('no_surat', 'No Surat', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('diusulkan', 'Diusulkan oleh', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tujuan', 'Ditujukan ke', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('tgl_kirim', 'Tanggal Kirim', 'trim|required');
        $this->form_validation->set_rules('melalui', 'Type', 'trim|required');
        $this->form_validation->set_rules('asal_surat', 'Asal Surat', 'trim|required');        
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
                    'asal_surat' => $this->input->post('asal_surat'),
                    'no_surat' => $this->input->post('no_surat'),
                    'perihal' => $this->input->post('perihal'),
                    'jenis' => $this->input->post('jenis'),
                    'tujuan' => $this->input->post('tujuan'),
                    'diusulkan' => $this->input->post('diusulkan'),
                    'tgl_kirim' => $this->input->post('tgl_kirim'),
                    'melalui' => $this->input->post('melalui'),
                    'catatan' => $this->input->post('catatan'),
                    'signer' => $this->input->post('signer'),
                    'jenis_ttd'=> $this->input->post('jenis_ttd'),
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

    function edit($id)
    {
        $query = $this->db->query('SELECT id_surat_keluar, no_surat, perihal, jenis, diusulkan, tujuan, melalui, tgl_kirim, catatan, signer, asal_surat FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        $data['id'] = $query->row();
        $this->load->view($this->dir_v.'edit',$data);
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
            // $action = '<a href="" class="view_act_btn" data-id="'.$id->id_surat_keluar.'" title="View Detail" style="color:#0F9647;"><button><i class="fa fa-search"></i></button></a>';
            if($id->status==3){
                $this->setTokenExpired($id->id_surat_keluar);
            }
            $data[] = array(
                "DT_RowId" => $id->id_surat_keluar,
                '0' => $i++,
                '1' => $id->no_surat,
                '2' => $this->m_surat_keluar_admin->user_surat_keluar($id->user),
                '3' => $id->tgl_kirim,
                // '4' => $id->diusulkan,
                // '5' => $id->jenis,
                '4' => $this->m_surat_keluar_admin->label_jenis_ttd($id->jenis_ttd),
                '5' => $this->m_surat_keluar_admin->label_status_keluar($id->status, $id->id_surat_keluar),
                '6' => $this->m_surat_keluar_admin->attachment(array($id->attach1)).' '.$this->m_surat_keluar_admin->keluar_act_btn($id->id_surat_keluar, $id->no_surat).' '.$this->m_surat_keluar_admin->attachment_downloaded(array($id->file_downloaded)),
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

    function act_del()
    {
        $id = $this->input->post('id_surat');
        $query = $this->db->query('SELECT * FROM app_surat_keluar WHERE id_surat_keluar='.$id.' LIMIT 1');
        // $row = $query->row_array();
        $rows = $query->row();
        $status=$rows->status;
        $notif=[];
        $notif=['status'=>1,'notif'=>'Something wrong'];
        if($status==0 || $status==1){
            $old_pic = $rows->path_folder;
            if(file_exists($old_pic)){
                unlink($old_pic);
            }
            $this->db->where('id_surat_keluar', $id);
            $this->db->delete('app_surat_keluar');
            $notif['notif'] = 'Data surat berhasil di hapus !';
            $notif['status'] = 2;
            
        }else{
            $notif['notif'] = 'Penghapusan data surat Gagal. Ijin hapus data Ditolak. !';
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
            $document_name = pathinfo($tempName, PATHINFO_FILENAME);
            $fileName = $this->l_surat_keluar_admin->RandStr2(10).".".$tempExt;
            $tahun = $this->l_surat_keluar_admin->DateYear();
            $bulan = $this->l_surat_keluar_admin->DateMonth();
            $targetPath = './upload/keluar/'.$tahun.'/'.$bulan.'/';
            $targetFile = $targetPath.$fileName;
            if(!file_exists($targetPath)){mkdir($targetPath, 0777, true);}
            $linkAttach = $tahun.'/'.$bulan.'/'.$fileName;
            $get_attach = $this->db->query('SELECT * FROM app_surat_keluar WHERE document_name="'.$document_name.'" LIMIT 1');
            $rows = $get_attach->row();
            $get_attach_exist = $this->db->query('SELECT * FROM app_surat_keluar WHERE id_surat_keluar="'.$id_surat.'" LIMIT 1');
            $rows_exist = $get_attach_exist->row();
            if(empty($rows->id_surat_keluar)){
                if($rows_exist->status !=5){
                    if(!empty($this->cek_file_attach($id_surat))){
                        if(move_uploaded_file($tempFile, $targetFile)){
                            $data_update = $this->cek_file_attach($id_surat);
                            $update[$data_update] = $linkAttach;
                            $t_file='upload/keluar/'.$tahun.'/'.$bulan.'/'.$fileName;
                            $real_t_file=realpath(APPPATH . '../' . $t_file);
                            $update['status'] = 1;
                            $update['path_folder'] = $real_t_file;
                            $update['document_name']=$document_name;
                            $this->db->where('id_surat_keluar', $id_surat);
                            $this->db->update('app_surat_keluar', $update);
                        }else{
                            header("HTTP/1.0 400 Bad Request");
                            echo 'Terjadi kesalahan saat upload file ke local !';
                        }
                    }else{
                        $old_pic = $rows_exist->path_folder;
                        if(file_exists($old_pic)){
                            unlink($old_pic);
                        }
                        if(move_uploaded_file($tempFile, $targetFile)){
                            $data_update = $this->cek_file_attach($id_surat);
                            $update['attach1'] = $linkAttach;
                            $t_file='upload/keluar/'.$tahun.'/'.$bulan.'/'.$fileName;
                            $real_t_file=realpath(APPPATH . '../' . $t_file);
                            $update['status'] = 1;
                            $update['path_folder'] = $real_t_file;
                            $update['document_name']=$document_name;
                            $this->db->where('id_surat_keluar', $id_surat);
                            $this->db->update('app_surat_keluar', $update);
                        }else{
                            header("HTTP/1.0 400 Bad Request");
                            echo 'Terjadi kesalahan saat upload file ke server !';
                        }
                    }
                }else{
                    header("HTTP/1.0 400 Bad Request");
                    echo 'Terjadi kesalahan saat upload file ke server karena document sudah selesai ditandatangani!';
                }
            }else{
                header("HTTP/1.0 400 Bad Request");
                echo 'Attachment file sudah ada. Silahkan ganti file anda !';
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
        // $data['id']=$this->session->userdata('sess_id');
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
                    $disetujui = $this->l_surat_keluar_admin->FilterArray($this->input->post('disetujui'));
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
                    $token_time = $this->l_surat_keluar_admin->DateTimeNow();
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