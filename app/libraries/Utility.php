<?php namespace App\library;
class Utility{
	var $skey 	= "nikunjJ_Shingala"; // you can change it
	//default construction
	public function __construct(){
    }
	//for SEO url
	public function SEOUrl($string){
		$pattern = '/[^a-zA-Z 0-9 -]+/i';
		$replacement = '';
		$string = preg_replace($pattern, $replacement, $string);
		return $catURL= str_replace(" ","-",$string);
	}
	public function uploadPhoto($path,$name){
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png|bmp';
		$config['max_size']	= '10000';
		$this->load->library('upload', $config);
		$this->upload->do_upload($name);
		$uploadFileData = $this->upload->data();
		return $uploadFileData['file_name'];
	}
	 public  function safe_b64encode($string) {
 
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
 
	public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
 
    public  function encode($value){ 
 		
    	if($value==""){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext)); 
    }
 
    public function decode($value){
 
        if($value==""){return false;}
        $crypttext = $this->safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
	//function for encode text
	public function encodeTex($text){
		return addslashes(trim($text));
	}
	//function for decode text
	public function decodeText($text){
		return stripcslashes($text);
	}
	//create invoice number
	public function generateInvoiceNo($tableName){
		//get last order number from order table
		$lastRecord = $this->tabledata->selectRecords(array(),$tableName,$condition);
			
	}
	//convert mysql date to us date
	public function convertUsDate($date){
		return date('m-d-Y',strtotime($date));
	}
	//convert date to mysql format
	public function convertIndianDate($date){
		$dateArray = explode('/',$date);
		return $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];
	}
	//convert mysql to date format
	public function convertIndianDateMysql($date){
		$dateArray = explode('-',$date);
		return $dateArray[2].'/'.$dateArray[1].'/'.$dateArray[0];
	}
	//convert mysql to date format for forntend
	public function convertIndianDateMysqlFront($date){
		$dateArray = explode('-',$date);
		return $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];
	}
	//convert mysql date format
	public function convertMysqlDate($date){
		$dateArray = explode('/',$date);
		return $dateArray[2].'-'.$dateArray[0].'-'.$dateArray[1];
	}
	//get sales
	public function getSales($d){
		$date = date('Y').'-'.date('m').'-'.$d;
		$CI =& get_instance();
		$CI->load->model('tabledata');
		$sumD = $CI->tabledata->sumVamlue('rui_order_master',array('dorder_date'=>$date,'vstatus'=>'enable'),'vsub_total');
		if($sumD[0]->vsub_total==NULL){
			echo "0.00";
		}else{
			echo $sumD[0]->vsub_total;
		}
	}
	//date calulcation like last day,first day
	function week_start_date($wk_num, $yr, $first = 1,  $format = 'F d, Y'){
		$wk_ts  = strtotime('+' . $wk_num . ' weeks', strtotime($yr . '0101'));
		$mon_ts = strtotime('-' . date('w', $wk_ts) + $first . ' days', $wk_ts);
		return date($format, $mon_ts);
	}

	 //Genrate the unique Coupen code	
     public function generateRandomString($length)
     {
         $characters = 
date("Ymdhis").'67890ABCDEFGHIJKL'.date("Ymdhis").'MNOPQRSTUVWXYZ12345'.date("Ymdhis");
         $randomString = '';
         for ($i = 0; $i < $length; $i++)
         {
             $randomString .= $characters[rand(0, strlen($characters) - 1)];
         }
         return $randomString;
     }

	
	//mail function
	function sendMail($row){
		$CI =& get_instance();
		$to = $row['to'];
		$subject = $row['subject'];
		$data=$row['extraData'];
		$message = $CI->load->view($row['templatePath'],$data,true);
		/*echo $message;
		exit;*/
		$from = $row['from'];
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: $row[fromTitle] <$from>" . "\r\n";
		return mail($to,$subject,$message,$headers);
	}
}
?>