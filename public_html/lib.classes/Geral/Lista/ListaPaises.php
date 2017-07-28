<?php

importar("Geral.Lista");
importar("Geral.Pais");

class ListaPaises extends Lista {
	
	const ID			= 'id';
	const NOME			= 'nome';
	const DDI			= 'ddi';
	
	public function __construct(){
		
		parent::__construct('pais');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Pais($info[self::ID]);
			
			$temp->nome			= $info[self::NOME];
			$temp->ddi			= $info[self::DDI];	
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Pais &$obj){
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::NOME.", ".self::DDI.") VALUES('".$obj->nome."','".$obj->ddi."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$obj = $l->listar();
		
	}
	
	public function alterar(Pais $obj){
		
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 		$obj->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DDI,		$obj->ddi, $where);	
		
	}
	
	public function deletar(Pais $obj){
		
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		if($obj->getEstados()->getTotal() > 0)
			throw new Exception("Há Estados cadastrados com este País");
		else
			$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>