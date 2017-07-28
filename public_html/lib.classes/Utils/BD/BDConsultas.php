<?php

class BDConsultas {
	  
	  public static function __VariaveisPadroes($q = 0){
	       
		   $var['num'] = 0;
		   $var['pos'] = 0;
		   if($var['num'] > 0) $var['limite'] = ($var['inicio']*$var['num'])+$var['num'];
		   else $var['limite'] = 999999;
		   
		   if($q > 0){
		       
			   $var['inicioS'] = 0;
		       $var['numS'] = 0;
		       $var['limiteS'] = ($var['inicioS']*$var['numS'])+$var['numS'];
			   
		   }
		   
		   return $var;
		  
	  }
	  
	  public static function consultarCondicoes($tabela, $inicio, $where = '', $inteiro = '', $id = ''){
	       
		   $con = BDConexao::__Abrir();
		   //$con->erros = false;
		   $max = BDConexao::__Abrir();
		   
		   $tabela = strtolower($tabela);
		   
		   if(!empty($where)){
		       
			   $sql = "SELECT * FROM ".$tabela." ".$where;
			   
		   }elseif(!empty($inteiro)){
		       
			   $sql = $inteiro;
			   
		   }elseif(!empty($id)){
		       
			   $sql = "SELECT * FROM ".$tabela." WHERE id = '".$id."'";
			   
		   }else{
		       
			   $sql = "SELECT * FROM ".$tabela;
			   
		   }
		   //$con->showErros();
		   //echo $sql." LIMIT ".$inicio.", 1<br>";
		   if(preg_match("!rand\(\)!", $sql)){
		   	$con->executar($sql." LIMIT 0, 1");
		   }else{
		   	$con->executar($sql." LIMIT ".$inicio.", 1");
		   }
		   $max->executar($sql);
		   
		   $conj['con'] = $con;
		   $conj['max'] = $max;
		   
		   return $conj;
		    
	  }
	  
	  public static function consultarExistencia($tabela, $where){
	       
		   $tabela = strtolower($tabela);
		   
		   $con = BDConexao::__Abrir();
		   $con->consultar($tabela, $where);
		   
		   return ($con->getTotal() > 0) ? true : false;
		   
	  }
	  
}

?>