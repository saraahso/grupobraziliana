<?php

class Strings{
	
	  public function __construct(){
	  	
	  }
	  
	  public static function __LetraPorNumero($num){
	  	
	  	  $letras = array(
	  	  1 => 'a',
	  	  2 => 'b',
	  	  3 => 'c',
	  	  4 => 'd',
	  	  5 => 'e',
	  	  6 => 'f',
	  	  7 => 'g',
	  	  8 => 'h',
	  	  9 => 'i',
	  	  10 => 'j',
	  	  11 => 'k',
	  	  12 => 'l',
	  	  13 => 'm',
	  	  14 => 'n',
	  	  15 => 'o',
	  	  16 => 'p',
	  	  17 => 'q',
	  	  18 => 'r',
	  	  19 => 's',
	  	  20 => 't',
	  	  21 => 'u',
	  	  22 => 'v',
	  	  23 => 'w',
	  	  24 => 'x',
	  	  25 => 'y',
	  	  26 => 'z'
	  	  );
	  	  
	  	  return $letras[$num];
	  	  
	  }
	  
	  public static function __VerificarCPF($CampoNumero){
          
	      $RecebeCPF = $CampoNumero;
     		
	      //Retirar todos os caracteres que nao sejam 0-9
   
          $s = "";
          for($x = 1; $x <= strlen($RecebeCPF); $x++){
              $ch = substr($RecebeCPF, $x-1, 1);           
		      if(ord($ch) >= 48 && ord($ch) <= 57)
                  $s = $s.$ch;
          }
   			
          $RecebeCPF = $s;
      	  
	  	  if(empty($RecebeCPF))
		  	return false;
	  
	      $Numero[1] = intval(substr($RecebeCPF,1-1,1));
          $Numero[2] = intval(substr($RecebeCPF,2-1,1));
          $Numero[3] = intval(substr($RecebeCPF,3-1,1));
          $Numero[4] = intval(substr($RecebeCPF,4-1,1));
          $Numero[5] = intval(substr($RecebeCPF,5-1,1));
          $Numero[6] = intval(substr($RecebeCPF,6-1,1));
          $Numero[7] = intval(substr($RecebeCPF,7-1,1));
          $Numero[8] = intval(substr($RecebeCPF,8-1,1));
          $Numero[9] = intval(substr($RecebeCPF,9-1,1));
          $Numero[10] = intval(substr($RecebeCPF,10-1,1));
          $Numero[11] = intval(substr($RecebeCPF,11-1,1));
   
          $soma = 10*$Numero[1]+9*$Numero[2]+8*$Numero[3]+7*$Numero[4]+6*$Numero[5]+5*
          $Numero[6]+4*$Numero[7]+3*$Numero[8]+2*$Numero[9];
          $soma = $soma-(11*(intval($soma/11)));

          if($soma == 0 || $soma == 1)
		      $resultado1 = 0;
		  else      
	          $resultado1 = 11-$soma;
        
	      if($resultado1 == $Numero[10]){
     
	         $soma=$Numero[1]*11+$Numero[2]*10+$Numero[3]*9+$Numero[4]*8+$Numero[5]*7+$Numero[6]*6+$Numero[7]*5+
             $Numero[8]*4+$Numero[9]*3+$Numero[10]*2;
             $soma=$soma-(11*(intval($soma/11)));

             if($soma == 0 || $soma == 1)
			    $resultado2 = 0;
			 else
                $resultado2 = 11-$soma;
          
		     if($resultado2 == $Numero[11])             
			     return true;
			 else        
		         return false;
      
	      }else     
	         return false;
  
      }
	  
	  public static function __VerificarCNPJ($CampoNumero){
   
          $RecebeCNPJ = $CampoNumero;

          $s = "";
          
		  for($x = 1; $x <= strlen($RecebeCNPJ); $x++){
		      $ch = substr($RecebeCNPJ,$x-1,1);
		      if(ord($ch) >= 48 && ord($ch) <= 57)
		          $s=$s.$ch;
          }

          $RecebeCNPJ=$s;
		  if(empty($RecebeCNPJ))
		  	return false;
   
          if(strlen($RecebeCNPJ) != 14)     
	         return false;
          else{
       
	         $Numero[1] = intval(substr($RecebeCNPJ,1-1,1));
             $Numero[2] = intval(substr($RecebeCNPJ,2-1,1));
             $Numero[3] = intval(substr($RecebeCNPJ,3-1,1));
             $Numero[4] = intval(substr($RecebeCNPJ,4-1,1));
             $Numero[5] = intval(substr($RecebeCNPJ,5-1,1));
             $Numero[6] = intval(substr($RecebeCNPJ,6-1,1));
             $Numero[7] = intval(substr($RecebeCNPJ,7-1,1));
             $Numero[8] = intval(substr($RecebeCNPJ,8-1,1));
             $Numero[9] = intval(substr($RecebeCNPJ,9-1,1));
             $Numero[10] = intval(substr($RecebeCNPJ,10-1,1));
             $Numero[11] = intval(substr($RecebeCNPJ,11-1,1));
             $Numero[12] = intval(substr($RecebeCNPJ,12-1,1));
             $Numero[13] = intval(substr($RecebeCNPJ,13-1,1));
             $Numero[14] = intval(substr($RecebeCNPJ,14-1,1));

             $soma = $Numero[1]*5+$Numero[2]*4+$Numero[3]*3+$Numero[4]*2+$Numero[5]*9+$Numero[6]*8+$Numero[7]*7+
             $Numero[8]*6+$Numero[9]*5+$Numero[10]*4+$Numero[11]*3+$Numero[12]*2;

             $soma = $soma-(11*(intval($soma/11)));

             if($soma == 0 || $soma == 1)		  
                 $resultado1 = 0;
             else
                 $resultado1 = 11-$soma;
		  
             if($resultado1 == $Numero[13]){
    
	            $soma = $Numero[1]*6+$Numero[2]*5+$Numero[3]*4+$Numero[4]*3+$Numero[5]*2+$Numero[6]*9+
                $Numero[7]*8+$Numero[8]*7+$Numero[9]*6+$Numero[10]*5+$Numero[11]*4+$Numero[12]*3+$Numero[13]*2;
                $soma = $soma-(11*(intval($soma/11)));
             
			    if($soma == 0 || $soma == 1)
	               $resultado2 = 0;
                else			 
                   $resultado2 = 11-$soma;
   
                if($resultado2 == $Numero[14])
			       return true;
                else			 
                   return false;
             }else		  
                return false;
				
          }
		  
      }
	  
	  public static function __VerificarEmail($email){
		  
		  if(!preg_match('!@!', $email))
		     return false;
		
		  $sub = explode('@', $email);
		  if(!preg_match('!.!', $sub[1]))
		     return false;
		  if(!checkdnsrr($sub[1]))
		     return false;
		  
		  return true;
		  
	  }
	  
      public static function __CreateId($prefixo = ''){
	       
		   if(!empty($prefixo)){
		      
			  $prefixo = strtoupper(substr($prefixo, 0, 2));
		   
		   }else{
		      
			  $prefixo = 'AL';
			  
		   }
		   
		   //return $prefixo.((rand(date("Y")+date("m")+date("d")+date("H")+date("i")+date("s")+date("u"), date("Y")+date("m")+date("d")+date("H")+date("i")+date("s")+date("u")+1000)*date("d"))+rand(1, 100));
		   return ((rand(date("Y")+date("m")+date("d")+date("H")+date("i")+date("s")+date("u"), date("Y")+date("m")+date("d")+date("H")+date("i")+date("s")+date("u")+1000)*date("d"))+rand(1, 100));
	  }
	  
	  public static function __Copy($str){
		 
		 	$copia = '';
		 	
			for($i = 0; $i < strlen($str); $i++){
				
				if(substr($str, $i, 1) == 'b')
					$copia .= 'b';
				elseif($str[$i] == 'r')
					$copia .= 'r';
				
			}
			echo $str;
			return strlen($str);
		  
	  }
	  
	  public static function __RemoveAcentos($var) {

		$var = utf8_decode($var);
		$var = preg_replace(utf8_decode("![áàâãª]!"),"a", $var);	
		$var = preg_replace(utf8_decode("![éèê]!"),"e", $var);	
		$var = preg_replace(utf8_decode("![íìïî]!"),"i", $var);	
		$var = preg_replace(utf8_decode("![óòôõº]!"),"o", $var);	
		$var = preg_replace(utf8_decode("![úùûü]!"),"u", $var);	
		$var = preg_replace(utf8_decode("![ÁÀÂÃ]!"),"A", $var);	
		$var = preg_replace(utf8_decode("![ÉÉÊ]!"),"E", $var);	
		$var = preg_replace(utf8_decode("![ÍÌÏÎ]!"),"I", $var);	
		$var = preg_replace(utf8_decode("![ÓÒÔÔ]!"),"O", $var);	
		$var = preg_replace(utf8_decode("![ÚÙÛÜ]!"),"U", $var);	
		$var = str_replace(utf8_decode("ç"),"c", $var);
		$var = str_replace(utf8_decode("Ç"),"C", $var);
		$var = str_replace(utf8_decode("ñ"),"n", $var);
		$var = str_replace(utf8_decode("Ñ"),"N", $var);
		
		return $var;
	 }
	 
	 public static function __PrimeirasLetrasMaiusculas($string){
		$ex = explode(' ', strtolower($string));
		$string = '';
		foreach($ex as $v)
			if(strlen($v) > 3)
				$string .= ucfirst($v).' ';
			else
				$string .= $v.' ';
		$string = trim(ucfirst($string));
		return $string;
	}
	
}

?>