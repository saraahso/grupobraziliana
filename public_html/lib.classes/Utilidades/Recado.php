<?php

importar("Geral.Objeto");
importar("Geral.Texto");
importar("Utils.Dados.DataHora");

class Recado extends Objeto {
	
	private $texto;
	private	$data;
	private	$sessao;
	private $idSessao;
	private	$liberado;
	
	public 	$local;
	public	$nome;
	public	$email;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->url 		= new URL;
		$this->texto 	= new Texto;
		$this->data 	= new DataHora;
		$this->local	= '';
		$this->nome		= '';
		$this->email	= '';
		$this->sessao 	= '';
		$this->idSessao = '';
		$this->liberado = false;
		
	}
	
	public function setSessao($sessao, $id){
		
		$this->sessao 	= $sessao;
		$this->idSessao = $id;
		
	}
	
	public function getSessao(){
		return $this->sessao;
	}
	
	public function getIdSessao(){
		return $this->idSessao;
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
	
	public function liberar(){
		$this->liberado = true;	
	}
	
	public function trancar(){
		$this->liberado = false;	
	}
	
	public function getSituacao(){
		return $this->liberado;
	}
	
}

?>