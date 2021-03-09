<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_admin extends CI_Model {
	public function __construct(){
            parent::__construct();
	}

	function select_level($data)
	{
		$query = $this->db->query('SELECT * FROM conf_level ORDER BY id_level');
		if(empty($data)){
			foreach($query->result() as $id) {
				echo '<option value="'.$id->id_level.'">'.$id->name.'</option>';
			}
		}else{
			foreach($query->result() as $id) {
				if(strstr($data, $id->id_level) != FALSE){
					echo '<option value="'.$id->id_level.'" selected="selected">'.$id->name.'</option>';
				}else{
					echo '<option value="'.$id->id_level.'">'.$id->name.'</option>';
				}
			}
		}
	}

	function list_menu($data, $akses)
	{
		$query = $this->db->query('SELECT id_menu,name FROM conf_menu WHERE akses='.$akses.' and sub=2 ORDER BY position');
		if(empty($data)){
			foreach($query->result() as $id) {
				echo '<option value="'.$id->id_menu.'">'.$id->name.'</option>';
			}
		}else{
			foreach($query->result() as $id) {
				if($data == $id->id_menu){
					echo '<option value="'.$id->id_menu.'" selected="selected">'.$id->name.'</option>';
				}else{
					echo '<option value="'.$id->id_menu.'">'.$id->name.'</option>';
				}
			}
		}
	}

	function list_cat_menu()
	{
		$cat_menu = array(
			"1" => "Backend",
			"2" => "Frontend"
		);
		foreach($cat_menu as $id => $val) {
			echo '<option value="'.$id.'">'.$val.'</option>';
		}
	}

	function list_icon($data)
	{
		$query = $this->db->query('SELECT id_icon,nama FROM conf_icon ORDER BY id_icon');
		if(empty($data)){
			foreach($query->result() as $id) {
				echo '<option value="'.$id->id_icon.'">&nbsp;&nbsp;'.$id->nama.'</option>';
			}
		}else{
			foreach($query->result() as $id) {
				if($data == $id->id_icon){
					echo '<option value="'.$id->id_icon.'" selected="selected">&nbsp;&nbsp;'.$id->nama.'</option>';
				}else{
					echo '<option value="'.$id->id_icon.'">&nbsp;&nbsp;'.$id->nama.'</option>';
				}
			}
		}
	}

}