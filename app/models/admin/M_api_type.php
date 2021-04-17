<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class M_api_type extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    function select_api_type()
    {
    	$query = $this->db->query('SELECT id, type, status FROM api_activation');
		foreach($query->result() as $id) {
			if($id->status=="active"){
				echo '<option value="'.$id->id.'" selected="selected">'.$id->type.'</option>';
			}else{
				echo '<option value="'.$id->id.'">'.$id->type.'</option>';
			}
		}
    }
}