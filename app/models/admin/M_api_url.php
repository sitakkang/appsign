<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class M_api_url extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    function api_act_btn($id,$key_,$type)
	{
		$return_data='<a class="btn edit_act_btn" data-id="'.$id.'" title="Edit" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5; color:#0F9647;"><i class="fas fa-edit"></i></a> | <a class="btn delete_act_btn" data-id="'.$id.'" data-key_="'.$key_.'" data-type="'.$type.'" title="Hapus" style="height: 22px;padding: 1px 5px;font-size: 12px;line-height: 1.5;color:#e51220;"><i class="fa fa-trash"></i></a>';
		
		return $return_data;
	}

	function select_type($data)
    {
        $query = $this->db->query('SELECT id, type FROM api_activation');
        if(empty($data)){
        	foreach($query->result() as $id) {
				echo '<option value="'.$id->id.'">'.$id->type.'</option>';
			}
        }else{
        	foreach($query->result() as $id) {
				if(strstr($data, $id->id) != FALSE){
					echo '<option value="'.$id->id.'" selected="selected">'.$id->type.'</option>';
				}else{
					echo '<option value="'.$id->id.'">'.$id->type.'</option>';
				}
			}
        }
		
	}

	function type_api($id)
	{
		$query = $this->db->query('SELECT type FROM api_activation WHERE id='.$id.' LIMIT 1');
		$type = $query->row();
		if(isset($type)){
			return $type->type;
		}else{
			return NULL;
		}
	}
}