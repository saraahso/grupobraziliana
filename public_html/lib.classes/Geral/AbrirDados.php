<?php

importar("Utils.BD.BDConsultas");

class AbrirDados{
	 
	  private 	$tabela;
	  private 	$parametros;
	  
	  public	$total;
	  
	  public function __construct($tabela, $parametros = ''){
	  	  
	  	  $this->tabela = $tabela;
	  	  if(!empty($parametros))
	  	     $this->parametros = $parametros;
	  	  else
	  	     $this->parametros = BDConsultas::variaveisPadroes();
	  	
	  }
	  
      public function abrirOpcoes(&$parametros = '', $where = '', $inteiro = '', $cod = ''){
	      
      	  if(!empty($parametros))
	  	     $this->parametros = $parametros;
      	
		  $query = BDConsultas::consultarCondicoes($this->tabela, $this->parametros['pos'], $where, $inteiro, $cod);
		  $info = $query['con']->getRegistro();
		  
		  $this->total = $query['max']->getTotal();
		  
		  $this->parametros['pos']++;

		  $parametros = $this->parametros;
		  
		  if((($query['con']->getTotal() > 0) && ($this->parametros['pos'] <= $this->parametros['limite']))){
		      
			  return $info;
			  
		  }else{
		      
			  return false;
			  
		  }
		  
	  }
	  
	  public static function __ArrayParaObjeto(&$obj, $array){
	  	  
	  	  if(is_array($array)){  
	  	
	  	     reset($array);
	  	     for($i = 1; $i <= count($array); $i++){
	  	  	  
	  	  	     $obj->{key($array)} = current($array);
	  	  	      next($array);
	  	  	
	  	     }
	  	     
	  	  }
	  	
	  }
	
}

?>