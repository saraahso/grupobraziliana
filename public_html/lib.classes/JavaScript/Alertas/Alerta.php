<?php

class Alerta {
    
	  protected static $new = 'alertas = new SexyAlert();';
	  protected static $javaScript = '';
	  
	  public static function _onComplete($comando){
	      
		   return "onComplete: function(returnValue){ ".$comando."}";
		  
	  }
	  
}

?>