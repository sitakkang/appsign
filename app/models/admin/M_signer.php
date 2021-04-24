<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class M_signer extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    function signer_act_btn($id)
	{
		$query = $this->db->query('SELECT * FROM t_signer where id="'.$id.'" LIMIT 1');
    	$data_ = $query->row();
    	$nama=$data_->name;
		$return_data='<a href="" class="view_act_btn" data-id="'.$id.'" title="View Detail" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;color:#0F9647;"><i class="fas fa-search"></i></a> | <a class="btn edit_act_btn" data-id="'.$id.'" title="Edit" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5; color:#0F9647;"><i class="fas fa-edit"></i></a> | <a href="" class="delete_act_btn" data-id="'.$id.'" data-name="'.$nama.'" title="Hapus" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;color:#e51220;"><i class="fa fa-trash"></i></a>';
		
		return $return_data;
	}

	function get_provincy($data)
    {
    	$nama="";
    	if(!empty($data)){
    		$query = $this->db->query('SELECT id, nama FROM provinsi where id="'.$data.'" LIMIT 1');
    		$id = $query->row();
    		$nama=$id->nama;
    	}
        return $nama; 
	}

	function get_kota($data)
    {
        $nama="";
    	if(!empty($data)){
    		$query = $this->db->query('SELECT id, nama FROM kota where id="'.$data.'" LIMIT 1');
    		$id = $query->row();
    		$nama=$id->nama;
    	}
        return $nama; 
	}

	function get_kecamatan($data)
    {
        $nama="";
    	if(!empty($data)){
    		$query = $this->db->query('SELECT id, nama FROM kecamatan where id="'.$data.'" LIMIT 1');
    		$id = $query->row();
    		$nama=$id->nama;
    	}
        return $nama; 
	}

	function get_desa($data)
    {
        $nama="";
    	if(!empty($data)){
    		$query = $this->db->query('SELECT id, nama FROM desa where id="'.$data.'" LIMIT 1');
    		$id = $query->row();
    		$nama=$id->nama;
    	}
        return $nama; 
	}

    function select_provincy($data)
    {
        $query = $this->db->query('SELECT id, nama FROM provinsi');
        if(empty($data)){
        	foreach($query->result() as $id) {
				echo '<option value="'.$id->id.'">'.$id->nama.'</option>';
			}
        }else{
        	foreach($query->result() as $id) {
				if(strstr($data, $id->id) != FALSE){
					echo '<option value="'.$id->id.'" selected="selected">'.$id->nama.'</option>';
				}else{
					echo '<option value="'.$id->id.'">'.$id->nama.'</option>';
				}
			}
        }
		
	}
	
	function select_kota($data)
    {
        $query = $this->db->query('SELECT id, nama FROM kota');
        if(empty($data)){
        	foreach($query->result() as $id) {
				echo '<option value="'.$id->id.'">'.$id->nama.'</option>';
			}
        }else{
        	foreach($query->result() as $id) {
				if(strstr($data, $id->id) != FALSE){
					echo '<option value="'.$id->id.'" selected="selected">'.$id->nama.'</option>';
				}else{
					echo '<option value="'.$id->id.'">'.$id->nama.'</option>';
				}
			}
        }
		
	}

	function select_kecamatan($data)
    {
        $query = $this->db->query('SELECT id, nama FROM kecamatan');
        if(empty($data)){
        	foreach($query->result() as $id) {
				echo '<option value="'.$id->id.'">'.$id->nama.'</option>';
			}
        }else{
        	foreach($query->result() as $id) {
				if(strstr($data, $id->id) != FALSE){
					echo '<option value="'.$id->id.'" selected="selected">'.$id->nama.'</option>';
				}else{
					echo '<option value="'.$id->id.'">'.$id->nama.'</option>';
				}
			}
        }
		
	}

	function select_desa($data)
    {
        $query = $this->db->query('SELECT id, nama FROM desa');
        if(empty($data)){
        	foreach($query->result() as $id) {
				echo '<option value="'.$id->id.'">'.$id->nama.'</option>';
			}
        }else{
        	foreach($query->result() as $id) {
				if(strstr($data, $id->id) != FALSE){
					echo '<option value="'.$id->id.'" selected="selected">'.$id->nama.'</option>';
				}else{
					echo '<option value="'.$id->id.'">'.$id->nama.'</option>';
				}
			}
        }
		
	}
}