<?php

importar('JavaScript.Alertas.Alerta');

class Aviso extends Alerta {
	  
	  public static function criar($msg, $pro = ''){
	       
		   if(!ereg(parent::$new, parent::$javaScript)){
		       
			   $codigo = parent::$new;
			   
		   }
		   
		   if(empty($pro)){
		       
			   $codigo .= 'alertas.alert("'.nl2br($msg).'");';
		   
		   }else{
		       
			   $codigo .= 'alertas.alert("'.nl2br($msg).'", { '.self:: _onComplete($pro).' });';
			   
		   }
		   
		   return parent::$javaScript .= $codigo;
		   
	  }
	  
}

?>