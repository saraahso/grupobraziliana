<?php

importar("Utils.CUrl");

class Arquivos {
     
	 public $arquivo;
	 public $url;
	 public $extensao;
	 public $nome;
	 
	 public function __construct($url = '', $open = false){
	     
		 if(preg_match("!".Sistema::$caminhoURL."!", $url))
		 	$url = str_replace(Sistema::$caminhoURL, Sistema::$caminhoDiretorio, $url);
		 
		 $this->url = $url;
		 
	     if($open) if(!empty($url)) $this->arquivo = self::__OpenArquivo($url);
		 
         $ext = explode('.', $url);
		 $ext = explode('?', $ext[count($ext)-1]);
	     $this->extensao = $ext[0];
		 
		 $tempN = $this->url;
		 
		 $tempN = str_replace('\\', '|', $tempN);
		 if(preg_match('!/!', $this->url))
		 	$tempN = str_replace('/', '|', $tempN);
			
		 $tempN = explode('|', $tempN);
		 
		 $ext = explode('.', $tempN[count($tempN)-1]);
		 $this->nome = '';
		 for($i = 0; $i < count($ext)-1; $i++) $this->nome .= $ext[$i];
		 
		 //$this->nome = str_replace(' ', '_', str_replace(',', '', $this->nome));
		 //$this->url = eregi_replace(' ', '_', eregi_replace(',', '', $this->url));
		 
	 }
	 
	 public function open(){
		 $this->arquivo = self::__OpenArquivo($this->url);
	 }
	 
	 public static function __OpenArquivo($url, $force = false){
		  
			if(preg_match("!http://!", $url) || $force){
		  		if(!preg_match("!".Sistema::$caminhoURL."!", $url) || $force)
					return self::__ModeCurl($url);
				else
					return self::__ModeArquivo(str_replace(Sistema::$caminhoURL, Sistema::$caminhoDiretorio, $url));
			}else{
				return self::__ModeArquivo($url);
			}

		  
	 }
	 
	 public static function __OpenArquivoByTEMP($temp){
		  
		  $arq = new Arquivos(str_replace("Program-Files", "Program Files", $temp['tmp_name']));
		  
		  $ext = explode('.', $temp['name']);
	      $arq->extensao = $ext[count($ext)-1];
		  
		  $arq->nome = '';
		  for($i = 0; $i < count($ext)-1; $i++) $arq->nome .= $ext[$i];
		  
		  $arq->nome = str_replace(' ', '-', str_replace(',', '',  str_replace('(', '',  str_replace(')', '',  str_replace('*', '',  str_replace('+', '', $arq->nome))))));
		  $arq->url = $arq->url;
		  
		  return $arq;
		  
	 }
	 
	 public static function __Create($str){
			
			$arq = new Arquivos;
			$arq->arquivo = $str;
			
			return $arq;
	 }
 
	 private static function __ModeCurl($url){
		  
	      if(function_exists('curl_init')){
		     
			 /*$ini = curl_init();
			 
			 @curl_setopt($ch, CURLOPT_HEADER, TRUE);
             @curl_setopt($ch, CURLOPT_NOBODY, TRUE);
             @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
             @curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
             curl_setopt($ini, CURLOPT_URL, $url);
             curl_setopt($ini, CURLOPT_HEADER, 0);
             ob_start();
             curl_exec($ini);
             curl_close($ini);
			 $var = ob_get_contents();
             ob_end_clean();
	      	
   	         return $var;*/
			 
			 //$f = fopen($url, 'r');
			 //return file_get_contents($url);
			 return CUrl::fetchContent($url);
		  
		  }else{
		     
			 return false;
			 
		  }
		  
	 }
	 
	 private static function __ModeArquivo($url){
	      
		  /*$arquivo = file($url);
		  
		  for($i = 0; $i < count($arquivo); $i++){
		      
			 $var .= $arquivo[$i];
			  
		  }*/
		  
		  return @file_get_contents($url);
		  
	 }
	 
	 public function saveArquivo($caminho, $modo = 'w+'){
		 
		  $f = fopen($caminho."/".$this->nome.".".$this->extensao, $modo);
		  fwrite($f, $this->arquivo);
		  fclose($f);
		  
		  return $this->nome.".".$this->extensao;
		 
	 }
	 
	 public function deleteArquivo(){
		  
		  self::__DeleteArquivo($this->url);
		  
	 }
	 
	 public static function __DeleteArquivo($caminho = ''){
		  
		  if(!empty($caminho)){
			  
			  @unlink($caminho);
			  
		  }
		  
	 }
	 
	 public function getNome(){
		
		if(!empty($this->nome))
			return $this->nome.".".$this->extensao;
		
	 }
	 
	 public function getSize(){
			
			return @filesize($this->url);
			
	 }
	 
}

?>