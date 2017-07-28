<?php

importar("Geral.Objeto");
importar("Geral.Pais");
importar("Geral.Estado");

class Cidade extends Objeto {
	
	private	$pais;
	private	$estado;
	
	public	$nome;
	public	$ddd;
	
	public function __construct($id = ''){
		parent::__construct($id);
		$this->pais = new Pais;
		$this->estado = new Estado;
	}
	
	public function setPais(Pais $obj){
		$this->pais = $obj;
	}
	
	public function getPais(){
		return $this->pais;
	}
	
	public function setEstado(Estado $obj){
		$this->estado = $obj;
	}
	
	public function getEstado(){
		return $this->estado;
	}
	
	public function __toString(){
		return $this->nome;
	}
	
}