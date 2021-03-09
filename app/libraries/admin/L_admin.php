<?php defined('BASEPATH') OR exit('No direct script access allowed');

class L_admin {

	function rand_str($length = 10, $specialCharacters = TRUE)
	{
		$digits = '';
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		if($specialCharacters === TRUE){$chars .= "!_?=/&+,.";}
		for($i=0;$i<$length; $i++){
			$x = mt_rand(0, strlen($chars) -1);
			$digits .= $chars[$x];
		}
		return $digits;
	}

	function encrypt_pass($password, $salt)
	{
		return sha1(md5($password).$salt);
	}

	function status_aktif($data)
	{
		switch ($data) {
			case 1:
				return 'Aktif';
				break;
			case 2:
				return 'Nonaktif';
				break;
			case 3:
				return 'Disable';
				break;
			default:
				return 'Undetected';
				break;
		}
	}

	function yes_or_no($data)
	{
		switch ($data) {
			case 1:
				return 'No';
				break;
			case 2:
				return 'Yes';
				break;
			default:
				return 'Undetected';
				break;
		}
	}

	function filter_array($input){
        $ganti = array('[', ']');
        $baru = array('', '');
        return str_replace($ganti, $baru, $input);
    }

}