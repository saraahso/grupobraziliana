<?php

class Email {
      
	  public static $para;
	  public static $de;
	  public static $assunto;
	  public static $msg;
	  public static $html = false;
	  
	  public static function enviar(){
			
	     if(self::$html == true){
		    
			$headers = "Content-type: text/html; charset=utf-8\r \n";
		
		 }
		 
		 $headers .= 'FROM: '.self::$de.'';
		 mail(self::$para, self::$assunto, self::$msg, $headers);
		  
	  }
	  
}

?>