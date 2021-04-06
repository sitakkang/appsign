<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class M_signer extends CI_Model {

    public function __construct(){
        parent::__construct();
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