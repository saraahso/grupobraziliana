<?php

importar("Geral.Objeto");
importar('Utils.Dados.DataHora');

class NoticiaUrgente extends Objeto {
	
	private	$data;
	
	public	$noticia;
	public	$ordem;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->data		= new DataHora;
		$this->noticia 	= '';
		$this->ordem	= 0;
		
	}
	
	public function setData(DataHora $dH){
		$this->data = $dH;
	}
	
	public function getData(){
		return $this->data;
	}
	
}

?>