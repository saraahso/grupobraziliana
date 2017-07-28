<?php

importar("Geral.Objeto");
importar("Geral.Texto");
importar("Utils.Dados.DataHora");

class Noticia extends Objeto {
	
	private $url;
	private $texto;
	private	$data;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->url 		= new URL;
		$this->texto 	= new Texto;
		$this->data 	= new DataHora;
		
	}
	
	public function setData(DataHora $data){
		$this->data = $data;	
	}
	
	public function getData(){
		return $this->data;	
	}
	
	public function setURL(URL $url){
		$this->url = $url;	
	}
	
	public function getURL(){
		return $this->url;	
	}
	
	public function setTexto(Texto $texto){
		$this->texto = $texto;	
	}
	
	public function getTexto(){
		return $this->texto;	
	}
	
}

?>