<?php defined('BASEPATH') OR exit('No direct script access allowed');

class L_surat_keluar_admin {

    function DateNow()
    {
    	date_default_timezone_set('UTC');
    	return gmdate('Y-m-d', time()+60*60*7);
    }

    function DateYear()
    {
    	date_default_timezone_set('UTC');
    	return gmdate('Y', time()+60*60*7);
    }

    function DateMonth()
    {
    	date_default_timezone_set('UTC');
    	return gmdate('m', time()+60*60*7);
    }

    function DateTimeNow()
    {
    	date_default_timezone_set('UTC');
    	return gmdate('Y-m-d H:i:s', time()+60*60*7);
    }

    function DateUpload()
    {
    	date_default_timezone_set('UTC');
    	return gmdate('Ymd', time()+60*60*7);
    }

    function RandStr1($length = 10, $specialCharacters = TRUE)
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

	function RandStr2($length = 10){
		$randstr = '';
		srand((double)microtime()*1000000);
		for($i=0;$i<$length;$i++){
		    $n = rand(48,120);
		    while (($n >= 58 && $n <= 64) || ($n >= 91 && $n <= 96)){
		        $n = rand(48,120);
		    }
		    $randstr .= chr($n);
		}
		return $randstr;
	}

	function EncryptPass($password, $salt)
	{
		return sha1(md5($password).$salt);
	}

	function YesOrNo($data)
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

	function StatusAktif($data)
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

	function FileSize($filesize){
		if(is_numeric($filesize)){
		$decr = 1024; $step = 0;
		$prefix = array('Byte','KB','MB','GB','TB','PB');
			while(($filesize / $decr) > 0.9){
			    $filesize = $filesize / $decr;
			    $step++;
			} 
			return round($filesize,2).' '.$prefix[$step];
		}else{
			return 'NaN';
		}
	}

	function FilterNumber($data)
    {
    	$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
        return preg_replace("/[^0-9 ]/", "", $data);
    }

    function FilterText($data)
    {
    	$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
        return preg_replace("/[^a-zA-Z ]/", "", $data);
    }

	function FilterTextNumber($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return preg_replace("/[^a-zA-Z0-9.-_ ]/", "", $data);
	}

	function FilterInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	function FilterArray($input){
        $ganti = array('[', ']');
        $baru = array('', '');
        return str_replace($ganti, $baru, $input);
    }
}