<?php

ini_set("session.bug_compat_42", "off");
ini_set("session.bug_compat_warn", "off");
ini_set("memory_limit", "1024M");

//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: POST,GET');
//header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
//header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
date_default_timezone_set('America/Sao_Paulo');
ini_set('display_errors',	'Off');
ini_set("log_errors",   	'On');
error_reporting(E_ALL & ~E_DEPRECATED);

session_start();

//echo $_SESSION['sigla'];
include('conf.php');

if(!function_exists("importar")){
function importar($classe = ''){
   
   $nomeClasse = explode('.', $classe);
   
   $classe = 'lib.classes/'.str_replace('.', '/', $classe);
   
   if(file_exists(Sistema::$caminhoDiretorio.$classe.'.php')){
       
   	   if(!class_exists($nomeClasse[count($nomeClasse)-1])){
	     //echo $classe."<br>";
		  include_once(Sistema::$caminhoDiretorio.$classe.'.php');
	   }
   }elseif(file_exists(Sistema::$caminhoDiretorio.$classe)){
       
	   abrirPastasClasses(Sistema::$caminhoDiretorio.$classe);
	   
   }else{
      
	  echo 'Classe "'.Sistema::$caminhoDiretorio.$classe.'" n�o encontrada. <br/>';
	  exit;
	  
   }
   
}

function abrirPastasClasses($classe){
      
   $pasta = dir($classe);
   
   while($arquivo = $pasta->read()){
         
		 if($arquivo <> '.' && $arquivo <> '..' && $arquivo <> 'error_log' && $arquivo <> '_notes'){

			 if(ereg('.', $arquivo)){
			       if(!class_exists(str_replace('.php', '', $arquivo)))
				      include_once($classe.'/'.$arquivo);
				
			 }else{
			    
				abrirPastasClasses($classe.'/'.$arquivo);
				
			 }
			
		 }
		 
   }
	  
}
}

include('conexao.php');

importar("Geral.AbrirDados");
importar("Utils.Dados.Strings");
importar("Geral.Lista.ListaURLs");
importar("Geral.Lista.ListaTextos");
importar("Geral.Lista.ListaImagens");
	
?>