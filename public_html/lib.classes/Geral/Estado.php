<?php

importar("Geral.Objeto");
importar("Geral.Pais");
importar("Geral.Lista.ListaCidades");

class Estado extends Objeto {
	
	private	$pais;
	private	$cidades;
	
	public	$nome;
	public	$uf;
	
	public function __construct($id = ''){
		parent::__construct($id);
		
		$this->pais = new Pais;
		
		$this->cidades = new ListaCidades;
		$this->cidades->condicoes(array(1 => array('campo' => ListaCidades::ESTADO, 'valor' => $id)));
		
	}
	
	public function setPais(Pais $obj){
		$this->pais = $obj;
	}
	
	public function getPais(){
		return $this->pais;
	}
	
	public function getCidades(){
		return $this->cidades;
	}
	
	public function __toString(){
		return $this->nome;
	}
	
}