<?php

importar("Geral.Lista");
importar("Geral.Estado");
importar("Geral.Lista.ListaPaises");

class ListaEstados extends Lista {
	
	const ID			= 'id';
	const PAIS			= 'pais';
	const NOME			= 'nome';
	const UF			= 'uf';
	
	public function __construct(){
		
		parent::__construct('estado');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Estado($info[self::ID]);
			
			$temp->nome			= $info[self::NOME];
			$temp->uf			= $info[self::UF];
			
			$lP = new ListaPaises;
			$lP->condicoes('', $info[self::PAIS], ListaPaises::ID);
			if($lP->getTotal() > 0)
				$temp->setPais($lP->listar());
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Estado &$obj){
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::PAIS.", ".self::NOME.", ".self::UF.") VALUES('".$obj->getPais()->getId()."','".$obj->nome."','".strtoupper($obj->uf)."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$obj = $l->listar();
		
	}
	
	public function alterar(Estado $obj){
		
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PAIS, 		$obj->getPais()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 		$obj->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::UF,		strtoupper($obj->uf), $where);	
		
	}
	
	public function deletar(Estado $obj){
		
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		if($obj->getCidades()->getTotal() > 0)
			throw new Exception("Há Cidades cadastradas com este Estado");
		else
			$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>