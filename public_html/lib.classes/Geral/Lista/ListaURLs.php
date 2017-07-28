<?php

importar("Geral.Lista");
importar("Geral.URL");

class ListaURLs extends Lista {
	
	const ID			= 'id';
	const URL			= 'url';
	const TABELA		= 'tabela';
	const CAMPO			= 'campo';
	const VALOR			= 'valor';
	
	public function __construct(){
		
		parent::__construct('urls');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new URL($info[self::ID]);
			$temp->setURL($info[self::URL]);
			$temp->tabela	= $info[self::TABELA];
			$temp->campo	= $info[self::CAMPO];
			$temp->valor	= $info[self::VALOR];
			
			return $temp;
		
		}
		
	}
	
	public function inserir(URL &$url){
		
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::URL.", ".self::TABELA.", ".self::CAMPO.", ".self::VALOR.") VALUES('".$url->getURL()."','".$url->tabela."','".$url->campo."','".$url->valor."')");
		$id = $this->con->getId();
		$class 	= __CLASS__;
		$l		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$url = $l->listar();
		
	}
	
	public function alterar(URL $url){
		
		$where = "WHERE ".self::ID." = '".$url->getId()."'";
			
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::URL, 		$url->getURL(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TABELA, 	$url->tabela, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CAMPO, 	$url->campo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALOR, 	$url->valor, $where);
		
	}
	
	public function deletar(URL $url){
		
		$where = "WHERE ".self::ID." = '".$url->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>