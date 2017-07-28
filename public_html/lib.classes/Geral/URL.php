<?php

importar("Geral.Objeto");
importar("Utils.Dados.Strings");

class URL extends Objeto {
	
	public	$tabela;
	public	$campo;
	public	$valor;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->url 		= '';
		$this->tabela	= '';
		$this->campo	= '';
		$this->valor	= '';
		
	}
	
	public function setURL($url){
		$this->url = $url;	
	}
	
	public function getURL(){
		return $this->url;	
	}
	
	public static function cleanURL($url){
		return strtolower(Strings::__RemoveAcentos(str_replace("\\", "", str_replace("/", "", str_replace("\"", "", str_replace("'", "", str_replace(" ", "-", str_replace(" > ", "-", $url))))))));
	}
	
}

?>