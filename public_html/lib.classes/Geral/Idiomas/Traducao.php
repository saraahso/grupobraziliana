<?php

importar("Geral.Objeto");

class Traducao extends Objeto {
	
	private	$idIdioma;
	private	$idConteudo;
	private $tabelaConteudo;
	private	$campoConteudo;
	
	public 	$conteudo;
	public	$traducao;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->idIdioma			= '';
		$this->campoConteudo	= '';
		$this->idConteudo 		= '';
		$this->tabelaConteudo 	= '';
		$this->conteudo			= '';
		$this->traducao			= '';
		
	}
	
	public function setIdIdioma($id){
		$this->idIdioma = $id;
	}
	
	public function getIdIdioma(){
		return $this->idIdioma;	
	}
	
	public function setIdConteudo($id){
		$this->idConteudo = $id;	
	}
	
	public function getIdConteudo(){
		return $this->idConteudo;	
	}
	
	public function setTabelaConteudo($tabela){
		
		if(!eregi(Sistema::$BDPrefixo, $tabela))
			$this->tabelaConteudo = $tabela;
		else
			$this->tabelaConteudo = eregi_replace(Sistema::$BDPrefixo, '', $tabela);
		
	}
	
	public function getTabelaConteudo(){
		return $this->tabelaConteudo;	
	}
	
	public function setCampoConteudo($campo){
		$this->campoConteudo = $campo;	
	}
	
	public function getCampoConteudo(){
		return $this->campoConteudo;	
	}
	
}

?>