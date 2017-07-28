<?php

importar("Geral.Objeto");
importar("Geral.Lista.ListaEstados");

class Pais extends Objeto {
	
	private	$estados;
	
	public	$nome;
	public	$ddi;
	
	public function __construct($id = ''){
		parent::__construct($id);
		
		$this->estados = new ListaEstados;
		$this->estados->condicoes(array(1 => array('campo' => ListaEstados::PAIS, 'valor' => $id)));
		
	}
	
	public function getEstados(){
		return $this->estados;
	}
	
	public function __toString(){
		return $this->nome;
	}
	
}