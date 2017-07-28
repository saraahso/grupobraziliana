<?php

importar("Geral.Objeto");
importar("Utils.Arquivos");

class TicketPost extends Objeto {
	
	private $dataHora;
	private	$arquivo;
	
	public	$texto;
	public	$nome;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->dataHora 	= new DataHora;
		$this->texto		= '';
		$this->nome			= '';
		$this->arquivo		= Arquivos::__Create("");
		
	}
	
	public function setDataHora(DataHora $dT){
		$this->dataHora = $dT;
	}
	
	public function getDataHora(){
		return $this->dataHora;
	}
	
	public function setArquivo(Arquivos $a){
		$this->arquivo = $a;
	}
	
	public function getArquivo(){
		return $this->arquivo;
	}
	
}

?>